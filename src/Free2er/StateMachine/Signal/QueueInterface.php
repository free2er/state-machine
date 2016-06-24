<?php

namespace Free2er\StateMachine\Signal;

/**
 * Интерфейс очереди сигналов
 */
interface QueueInterface
{
    /**
     * Добавляет сигнал в очередь
     *
     * @param  SignalInterface $signal
     * @return void
     */
    public function enqueue(SignalInterface $signal);

    /**
     * Извлекает сигнал из очереди
     *
     * @return SignalInterface
     */
    public function dequeue(): SignalInterface;

    /**
     * Возвращает отсутствие сигналов в очереди
     *
     * @return boolean
     */
    public function isEmpty(): bool;
}
