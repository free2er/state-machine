<?php

namespace Free2er\StateMachine\Event;

use Zend\EventManager\Event;

/**
 * Событие менеджера событий Zend
 */
class ZendEvent extends Event implements EventInterface
{
    /**
     * Объект
     *
     * @var object
     */
    private $object;

    /**
     * Предыдущее состояние
     *
     * @var string
     */
    private $previous;

    /**
     * Следующее состояние
     *
     * @var string
     */
    private $next;

    /**
     * Сигнал
     *
     * @var string
     */
    private $signal;

    /**
     * Конструктор
     *
     * @param object $object
     * @param string $previous
     * @param string $next
     * @param string $event
     * @param string $machine
     * @param string $signal
     * @param array  $parameters
     */
    public function __construct(
        object $object,
        string $previous,
        string $next,
        string $event,
        string $machine,
        string $signal,
        array $parameters
    ) {
        parent::__construct($event, $machine, $parameters);

        $this->object   = $object;
        $this->previous = $previous;
        $this->next     = $next;
        $this->signal   = $signal;
    }

    /**
     * Возвращает объект
     *
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * Возвращает предыдущее состояние
     *
     * @return string
     */
    public function getPrevious(): string
    {
        return $this->previous;
    }

    /**
     * Возвращает следующее состояние
     *
     * @return string
     */
    public function getNext(): string
    {
        return $this->next;
    }

    /**
     * Возвращает наименование события
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->getName();
    }

    /**
     * Возвращает наименование машины состояний
     *
     * @return string
     */
    public function getMachine(): string
    {
        return $this->getTarget();
    }

    /**
     * Возвращает наименование сигнала
     *
     * @return string
     */
    public function getSignal(): string
    {
        return $this->signal;
    }

    /**
     * Возвращает параметры
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->getParams();
    }
}
