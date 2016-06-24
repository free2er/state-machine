<?php

namespace Free2er\StateMachine\Signal;

/**
 * Сигнал
 */
class Signal implements SignalInterface
{
    const EVENT_SIGNAL_PRE  = 'signal.%s.pre';
    const EVENT_SIGNAL_POST = 'signal.%s.post';

    /**
     * Наименование
     *
     * @var string
     */
    private $name;

    /**
     * Параметры
     *
     * @var array
     */
    private $parameters;

    /**
     * Конструктор
     *
     * @param string $name
     * @param array  $parameters
     */
    public function __construct(string $name, array $parameters = [])
    {
        $this->name       = $name;
        $this->parameters = $parameters;
    }

    /**
     * Возвращает наименование
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает параметры
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Возвращает события, инициируемые перед переходом
     *
     * @return array
     */
    public function getPreEvents(): array
    {
        return [sprintf(static::EVENT_SIGNAL_PRE, $this->getName())];
    }

    /**
     * Возвращает события, инициируемые по итогам перехода
     *
     * @return array
     */
    public function getPostEvents(): array
    {
        return [sprintf(static::EVENT_SIGNAL_POST, $this->getName())];
    }
}
