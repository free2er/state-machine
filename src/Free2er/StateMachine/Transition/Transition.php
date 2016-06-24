<?php

namespace Free2er\StateMachine\Transition;

use Free2er\Specification\SpecificationInterface;

/**
 * Переход
 */
class Transition implements TransitionInterface
{
    const EVENT_TRANSITION_PRE                = 'transition.pre';
    const EVENT_TRANSITION_POST               = 'transition.post';
    const EVENT_ACTION_STATE_EXIT_PRE         = 'action.%s.state.exit.pre';
    const EVENT_ACTION_STATE_EXIT_POST        = 'action.%s.state.exit.post';
    const EVENT_ACTION_STATE_ENTRY_PRE        = 'action.%s.state.entry.pre';
    const EVENT_ACTION_STATE_ENTRY_POST       = 'action.%s.state.entry.post';
    const EVENT_STATE_EXIT                    = 'state.%s.exit';
    const EVENT_STATE_ENTRY                   = 'state.%s.entry';
    const EVENT_STATE_ENTRY_BEFORE_TRANSITION = 'state.%s.entry.beforeTransition';
    const EVENT_STATE_ENTRY_AFTER_TRANSITION  = 'state.%s.entry.afterTransition';

    /**
     * @var string
     */
    private $previous;

    /**
     * @var string
     */
    private $next;

    /**
     * @var string
     */
    private $signal;

    /**
     * Спецификация
     *
     * @var SpecificationInterface
     */
    private $specification;

    /**
     * Действия
     *
     * @var string[]
     */
    private $actions = [];

    /**
     * Конструктор
     *
     * @param string                 $previous
     * @param string                 $next
     * @param string                 $signal
     * @param array                  $actions
     * @param SpecificationInterface $specification
     */
    public function __construct(
        string $previous,
        string $next,
        string $signal,
        SpecificationInterface $specification = null,
        array $actions = []
    ) {
        $this->previous      = $previous;
        $this->next          = $next;
        $this->signal        = $signal;
        $this->specification = $specification;

        foreach ($actions as $action) {
            $this->addAction($action);
        }
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
     * Возвращает события, инициируемые перед переходом
     *
     * @return array
     */
    public function getPreEvents(): array
    {
        $events = [static::EVENT_TRANSITION_PRE];

        foreach ($this->actions as $action) {
            $events[] = sprintf(static::EVENT_ACTION_STATE_EXIT_PRE, $action);
        }

        $events[] = sprintf(static::EVENT_STATE_EXIT, $this->previous);

        foreach ($this->actions as $action) {
            $events[] = sprintf(static::EVENT_ACTION_STATE_EXIT_POST, $action);
        }

        $events[] = sprintf(static::EVENT_STATE_ENTRY_BEFORE_TRANSITION, $this->next);

        return $events;
    }

    /**
     * Возвращает события, инициируемые по итогам перехода
     *
     * @return array
     */
    public function getPostEvents(): array
    {
        $events = [sprintf(static::EVENT_STATE_ENTRY_AFTER_TRANSITION, $this->previous)];

        foreach ($this->actions as $action) {
            $events[] = sprintf(static::EVENT_ACTION_STATE_ENTRY_PRE, $action);
        }

        $events[] = sprintf(static::EVENT_STATE_ENTRY, $this->next);

        foreach ($this->actions as $action) {
            $events[] = sprintf(static::EVENT_ACTION_STATE_ENTRY_POST, $action);
        }

        $events[] = static::EVENT_TRANSITION_POST;

        return $events;
    }

    /**
     * Возвращает является ли переходом по заданному сигналу
     *
     * @param  string $signal
     * @return boolean
     */
    public function forSignal(string $signal): bool
    {
        return $signal == $this->signal;
    }

    /**
     * Возвращает является ли переходом из заданного состояния
     *
     * @param  string $state
     * @return boolean
     */
    public function forState(string $state): bool
    {
        return $state == $this->previous;
    }

    /**
     * Возвращает является ли переходом для заданного объекта
     *
     * @param  object $object
     * @return boolean
     */
    public function forObject(object $object): bool
    {
        if (!$this->specification) {
            return true;
        }

        return $this->specification->isSatisfied($object);
    }

    /**
     * Добавляет действие
     *
     * @param  string $action
     * @return void
     */
    private function addAction(string $action)
    {
        $this->actions[] = $action;
    }
}
