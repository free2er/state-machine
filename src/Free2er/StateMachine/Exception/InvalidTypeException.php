<?php

namespace Free2er\StateMachine\Exception;

/**
 * Ошибка недопустимого типа
 */
class InvalidTypeException extends RuntimeException
{
    /**
     * Тип
     *
     * @var integer
     */
    private $type;

    /**
     * Конструктор
     *
     * @param string  $message
     * @param integer $type
     */
    public function __construct(string $message, int $type)
    {
        parent::__construct($message);

        $this->type = $type;
    }

    /**
     * Возвращает тип
     *
     * @return integer
     */
    public function getType(): int
    {
        return $this->type;
    }
}
