<?php

namespace Free2er\StateMachine;

use Free2er\StateMachine\Signal\SignalInterface;

/**
 * Интерфейс машины состояний
 */
interface MachineInterface
{
    /**
     * Возвращает возможность отправки сигнала для заданного объекта
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return boolean
     */
    public function isAllowed(object $object, SignalInterface $signal): bool;

    /**
     * Отправляет сигнал для заданного объекта
     *
     * @param  object          $object
     * @param  SignalInterface $signal
     * @return void
     */
    public function signal(object $object, SignalInterface $signal);

    /**
     * Актуализиует заданный объект
     *
     * @param  object $object
     * @return void
     */
    public function refresh(object $object);
}
