<?php

namespace Free2er\StateMachine\Event;

/**
 * Интерфейс обработчика событий
 */
interface EventHandlerInterface
{
    /**
     * Обрабатывает событие
     *
     * @param  EventInterface $event
     * @return void
     */
    public function handle(EventInterface $event);
}
