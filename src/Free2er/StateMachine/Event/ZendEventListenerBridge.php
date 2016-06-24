<?php

namespace Free2er\StateMachine\Event;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Мост обработчика событий Zend
 */
class ZendEventListenerBridge extends AbstractListenerAggregate
{
    /**
     * Обработчик события
     *
     * @var EventHandlerInterface
     */
    private $handler;

    /**
     * Наименование события
     *
     * @var string
     */
    private $event;

    /**
     * Приоритет обработки события
     *
     * @var integer
     */
    private $priority;

    /**
     * Конструктор
     *
     * @param EventHandlerInterface $handler
     * @param string                $event
     * @param integer               $priority
     */
    public function __construct(EventHandlerInterface $handler, string $event, int $priority = 1)
    {
        $this->handler  = $handler;
        $this->event    = $event;
        $this->priority = $priority;
    }

    /**
     * Подписывается на обработку событий
     *
     * @param  EventManagerInterface $events
     * @param  integer               $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach($this->event, [$this->handler, 'handle'], $this->priority ?: $priority);
    }
}
