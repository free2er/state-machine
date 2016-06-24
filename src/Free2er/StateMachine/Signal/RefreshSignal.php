<?php

namespace Free2er\StateMachine\Signal;

/**
 * Сигнал актуализации состояния
 */
class RefreshSignal implements SignalInterface
{
    const NAME = 'refresh';

    /**
     * Возвращает наименование
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * Возвращает параметры
     *
     * @return array
     */
    public function getParameters(): array
    {
        return [];
    }

    /**
     * Возвращает события, инициируемые перед переходом
     *
     * @return array
     */
    public function getPreEvents(): array
    {
        return [];
    }

    /**
     * Возвращает события, инициируемые по итогам перехода
     *
     * @return array
     */
    public function getPostEvents(): array
    {
        return [];
    }
}
