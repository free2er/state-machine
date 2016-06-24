<?php

namespace Free2er\StateMachine\Signal;

use SplQueue;

/**
 * Очередь сигналов
 */
class Queue implements QueueInterface
{
    /**
     * Очередь
     *
     * @var SplQueue
     */
    private $queue;

    /**
     * Добавляет сигнал в очередь
     *
     * @param  SignalInterface $signal
     * @return void
     */
    public function enqueue(SignalInterface $signal)
    {
        $this->getQueue()->enqueue($signal);
    }

    /**
     * Извлекает сигнал из очереди
     *
     * @return SignalInterface
     */
    public function dequeue(): SignalInterface
    {
        return $this->getQueue()->dequeue();
    }

    /**
     * Возвращает отсутствие сигналов в очереди
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return $this->getQueue()->isEmpty();
    }

    /**
     * Возвращает очередь
     *
     * @return SplQueue
     */
    private function getQueue(): SplQueue
    {
        if (!$this->queue) {
            $this->queue = new SplQueue();
        }

        return $this->queue;
    }
}
