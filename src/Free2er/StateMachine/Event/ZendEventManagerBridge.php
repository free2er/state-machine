<?php

namespace Free2er\StateMachine\Event;

use Zend\EventManager\EventManagerInterface as ZendManager;

/**
 * Мост менеджера событий Zend
 */
class ZendEventManagerBridge implements EventManagerInterface
{
    /**
     * Менеджер событий Zend
     *
     * @var EventManagerInterface
     */
    private $events;

    /**
     * Конструктор
     *
     * @param ZendManager $events
     */
    public function __construct(ZendManager $events)
    {
        $this->events = $events;
    }

    /**
     * Обрабатывает событие
     *
     * @param  object $object
     * @param  string $previous
     * @param  string $next
     * @param  string $event
     * @param  string $machine
     * @param  string $signal
     * @param  array  $parameters
     * @return void
     */
    public function trigger(
        object $object,
        string $previous,
        string $next,
        string $event,
        string $machine,
        string $signal,
        array $parameters
    ) {
        $event = new ZendEvent($object, $previous, $next, $event, $machine, $signal, $parameters);
        $this->events->triggerEvent($event);
    }
}
