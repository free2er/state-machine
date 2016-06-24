<?php

namespace Free2er\StateMachine\Exception;

/**
 * Ошибка перехода
 */
class TransitionException extends RuntimeException
{
    /**
     * Состояние
     *
     * @var string
     */
    private $state;

    /**
     * Наименование сигнала
     *
     * @var string
     */
    private $signal;

    /**
     * Наименование машины состояний
     *
     * @var string
     */
    private $machine;

    /**
     * Конструктор
     *
     * @param string $message
     * @param string $state
     * @param string $signal
     * @param string $machine
     */
    public function __construct(string $message, string $state, string $signal, string $machine)
    {
        parent::__construct($message);

        $this->state   = $state;
        $this->signal  = $signal;
        $this->machine = $machine;
    }

    /**
     * Возвращает состояние
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Возвращат наименование сигнала
     *
     * @return string
     */
    public function getSignal(): string
    {
        return $this->signal;
    }

    /**
     * Возвращает наименование машины состояний
     *
     * @return string
     */
    public function getMachine(): string
    {
        return $this->machine;
    }
}
