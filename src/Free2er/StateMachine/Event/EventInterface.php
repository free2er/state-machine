<?php

namespace Free2er\StateMachine\Event;

/**
 * Интерфейс события
 */
interface EventInterface
{
    /**
     * Возвращает объект
     *
     * @return object
     */
    public function getObject(): object;

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
     * Возвращает наименование события
     *
     * @return string
     */
    public function getEvent(): string;

    /**
     * Возвращает наименование машины состояний
     *
     * @return string
     */
    public function getMachine(): string;

    /**
     * Возвращает наименование сигнала
     *
     * @return string
     */
    public function getSignal(): string;

    /**
     * Возвращает параметры
     *
     * @return array
     */
    public function getParameters(): array;
}
