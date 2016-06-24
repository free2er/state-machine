<?php

namespace Free2er\StateMachine\Event;

/**
 * Интерфейс менеджера событий
 */
interface EventManagerInterface
{
    /**
     * Инициирует событие
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
    );
}
