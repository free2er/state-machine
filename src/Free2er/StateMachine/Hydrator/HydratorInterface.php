<?php

namespace Free2er\StateMachine\Hydrator;

/**
 * Интерфейс гидратора
 */
interface HydratorInterface
{
    /**
     * Возвращает идентификатор
     *
     * @param  object $object
     * @return string
     */
    public function getId(object $object): string;

    /**
     * Возвращает состояние
     *
     * @param  object $object
     * @return string
     */
    public function getState(object $object): string;

    /**
     * Устанавливат состояние
     *
     * @param  object $object
     * @param  string $state
     * @return void
     */
    public function setState(object $object, string $state);
}
