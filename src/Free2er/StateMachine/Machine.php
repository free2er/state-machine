<?php

namespace Free2er\StateMachine;

use Free2er\StateMachine\Event\EventManagerInterface;
use Free2er\StateMachine\Exception\InvalidTypeException;
use Free2er\StateMachine\Exception\TransitionException;
use Free2er\StateMachine\Hydrator\HydratorInterface;
use Free2er\StateMachine\Signal\Queue;
use Free2er\StateMachine\Signal\QueueInterface;
use Free2er\StateMachine\Signal\RefreshSignal;
use Free2er\StateMachine\Signal\SignalInterface;
use Free2er\StateMachine\Transition\TransitionInterface;

/**
 * Интерфейс машины состояний
 */
class Machine implements MachineInterface
{
    const MODE_SILENT = 0x00;
    const MODE_STRICT = 0xFF;

    const ERROR_TRANSITION_NOT_FOUND        = 0x01;
    const ERROR_DIRECT_TRANSITION_AMBIGUOUS = 0x02;
    const ERROR_SIGNAL_TRANSITION_AMBIGUOUS = 0x04;

    const EVENT_TYPE_PRE  = 0x00;
    const EVENT_TYPE_POST = 0x01;

    /**
     * Режим обработки ошибок
     *
     * @var integer
     */
    private $mode;

    /**
     * Наименование
     *
     * @var string
     */
    private $name;

    /**
     * Гидратор
     *
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * Менеджер событий
     *
     * @var EventManagerInterface
     */
    private $events;

    /**
     * Переходы
     *
     * @var TransitionInterface[]
     */
    private $transitions = [];

    /**
     * Очереди сигналов
     *
     * @var QueueInterface[]
     */
    private $queues = [];

    /**
     * Флаги текущих обновлений
     *
     * @var array
     */
    private $refreshes = [];

    /**
     * Конструктор
     *
     * @param integer               $mode
     * @param string                $name
     * @param HydratorInterface     $hydrator
     * @param EventManagerInterface $events
     * @param TransitionInterface[] $transitions
     */
    public function __construct(
        int $mode,
        string $name,
        HydratorInterface $hydrator,
        EventManagerInterface $events,
        array $transitions
    ) {
        $this->mode     = $mode;
        $this->name     = $name;
        $this->hydrator = $hydrator;
        $this->events   = $events;

        foreach ($transitions as $transition) {
            $this->addTransition($transition);
        }
    }

    /**
     * Возвращает возможность отправки сигнала для заданного объекта
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return boolean
     */
    public function isAllowed(object $object, SignalInterface $signal): bool
    {
        $this->refresh($object);
        return (boolean) $this->getAllowedTransitions($object, $signal);
    }

    /**
     * Отправляет сигнал для заданного объекта
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return void
     */
    public function signal(object $object, SignalInterface $signal)
    {
        $this->refresh($object);
        $this->getQueue($object)->enqueue($signal);
        $this->refresh($object);
    }

    /**
     * Актуализиует заданный объект
     *
     * @param  object $object
     * @return void
     */
    public function refresh(object $object)
    {
        $id = $this->hydrator->getId($object);

        if (isset($this->refreshes[$id])) {
            return;
        }

        $this->refreshes[$id] = true;

        $refresh = new RefreshSignal();
        $this->performRefresh($object, $refresh);

        $queue = $this->getQueue($object);

        while (!$queue->isEmpty()) {
            $this->performSignal($object, $queue->dequeue());
            $this->performRefresh($object, $refresh);
        }

        unset($this->refreshes[$id]);
    }

    /**
     * Выполняет прямые переходы
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return void
     * @throws TransitionException
     */
    private function performRefresh(object $object, SignalInterface $signal)
    {
        while (true) {
            $transitions = $this->getAllowedTransitions($object, $signal);

            if (count($transitions) > 1 && ($this->mode & static::ERROR_DIRECT_TRANSITION_AMBIGUOUS)) {
                throw $this->createTransitionAmbiguousException($object, $signal);
            }

            if (!$transitions) {
                return;
            }

            $this->performTransition($object, $signal, array_shift($transitions));
        }
    }

    /**
     * Выполняет переход по сигналу
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return void
     * @throws TransitionException
     */
    private function performSignal(object $object, SignalInterface $signal)
    {
        $transitions = $this->getAllowedTransitions($object, $signal);

        if (count($transitions) > 1 && ($this->mode & static::ERROR_SIGNAL_TRANSITION_AMBIGUOUS)) {
            throw $this->createTransitionAmbiguousException($object, $signal);
        }

        if (!$transitions && ($this->mode & static::ERROR_TRANSITION_NOT_FOUND)) {
            throw $this->createTransitionNotFoundException($object, $signal);
        }

        if (!$transitions) {
            return;
        }

        $this->performTransition($object, $signal, array_shift($transitions));
    }

    /**
     * Выполняет заданный переход
     *
     * @param  object              $object
     * @param  SignalInterface     $signal
     * @param  TransitionInterface $transition
     * @return void
     */
    private function performTransition(object $object, SignalInterface $signal, TransitionInterface $transition)
    {
        $this->handleEvents(static::EVENT_TYPE_PRE, $object, $signal, $transition);
        $this->hydrator->setState($object, $transition->getNext());
        $this->handleEvents(static::EVENT_TYPE_POST, $object, $signal, $transition);
    }

    /**
     * Инициирует события заданного перехода
     *
     * @param  integer             $type
     * @param  object              $object
     * @param  SignalInterface     $signal
     * @param  TransitionInterface $transition
     * @return void
     */
    private function handleEvents(int $type, object $object, SignalInterface $signal, TransitionInterface $transition)
    {
        switch ($type) {
            case static::EVENT_TYPE_PRE:
                $events1 = $signal->getPreEvents();
                $events2 = $transition->getPreEvents();
                break;

            case static::EVENT_TYPE_POST:
                $events1 = $transition->getPostEvents();
                $events2 = $signal->getPostEvents();
                break;

            default:
                throw $this->createInvalidEventTypeException($type);
        }

        $previous   = $transition->getPrevious();
        $next       = $transition->getNext();
        $parameters = $signal->getParameters();
        $signal     = $signal->getName();
        $machine    = $this->name;

        foreach (array_merge(array_values($events1), array_values($events2)) as $event) {
            $this->events->trigger($object, $previous, $next, $event, $machine, $signal, $parameters);
        }
    }

    /**
     * Создает ошибку неопределенного перехода
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return TransitionException
     */
    private function createTransitionAmbiguousException(object $object, SignalInterface $signal): TransitionException
    {
        $state  = $this->hydrator->getState($object);
        $signal = $signal->getName();

        return new TransitionException('Transition is ambiguous.', $state, $signal, $this->name);
    }

    /**
     * Создает ошибку отсутствующего перехода
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return TransitionException
     */
    private function createTransitionNotFoundException(object $object, SignalInterface $signal): TransitionException
    {
        $state  = $this->hydrator->getState($object);
        $signal = $signal->getName();

        return new TransitionException('Transition not found.', $state, $signal, $this->name);
    }

    /**
     * Создает ошибку недопустимого типа события
     *
     * @param  integer $type
     * @return InvalidTypeException
     */
    private function createInvalidEventTypeException(int $type): InvalidTypeException
    {
        return new InvalidTypeException('Event type is invalid.', $type);
    }

    /**
     * Добавляет переход
     *
     * @param  TransitionInterface $transition
     * @return void
     */
    private function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * Возвращает допустимые переходы
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return TransitionInterface[]
     */
    private function getAllowedTransitions(object $object, SignalInterface $signal): array
    {
        $signal  = $signal->getName();
        $state   = $this->hydrator->getState($object);
        $allowed = [];

        foreach ($this->transitions as $transition) {
            if (!$transition->forSignal($signal)) {
                continue;
            }

            if (!$transition->forState($state)) {
                continue;
            }

            if (!$transition->forObject($object)) {
                continue;
            }

            $allowed[] = $transition;
        }

        return $allowed;
    }

    /**
     * Возвращает очередь сигналов для заданного объекта
     *
     * @param  object $object
     * @return QueueInterface
     */
    private function getQueue(object $object)
    {
        $id = $this->hydrator->getId($object);

        if (!isset($this->queues[$id])) {
            $this->queues[$id] = new Queue();
        }

        return $this->queues[$id];
    }
}
