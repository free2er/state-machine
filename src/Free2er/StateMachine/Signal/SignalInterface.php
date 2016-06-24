<?php

namespace Free2er\StateMachine\Signal;

/**
 * Интерфейс сигнала
 */
interface SignalInterface
{
    /**
     * Возвращает наименование
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Возвращает параметры
     *
     * @return array
     */
    public function getParameters(): array;

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
}
