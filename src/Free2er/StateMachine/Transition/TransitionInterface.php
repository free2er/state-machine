<?php

namespace Free2er\StateMachine\Transition;

/**
 * Интерфейс перехода
 */
interface TransitionInterface
{
    /**
     * Возвращает предыдущее состояние
     *
     * @return string
     */
    public function getPrevious(): string;

    /**
     * Возвращает следующее состояние
     *
     * @return string
     */
    public function getNext(): string;

    /**
     * Возвращает события, инициируемые перед переходом
     *
     * @return array
     */
    public function getPreEvents(): array;

    /**
     * Возвращает события, инициируемые по итогам перехода
     *
     * @return array
     */
    public function getPostEvents(): array;

    /**
     * Возвращает является ли переходом по заданному сигналу
     *
     * @param  string $signal
     * @return boolean
     */
    public function forSignal(string $signal): bool;

    /**
     * Возвращает является ли переходом из заданного состояния
     *
     * @param  string $state
     * @return boolean
     */
    public function forState(string $state): bool;

    /**
     * Возвращает является ли переходом для заданного объекта
     *
     * @param  object $object
     * @return boolean
     */
    public function forObject(object $object): bool;
}
