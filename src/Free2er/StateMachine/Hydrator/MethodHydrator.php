<?php

namespace Free2er\StateMachine\Hydrator;

/**
 * Гидратор методов объекта
 */
class MethodHydrator implements HydratorInterface
{
    const ACCESSOR = 'get';
    const MODIFIER = 'set';

    /**
     * Свойство идентификатора
     *
     * @var string
     */
    private $id;

    /**
     * Свойство состояния
     *
     * @var string
     */
    private $state;

    /**
     * Конструктор
     *
     * @param string $id
     * @param string $state
     */
    public function __construct(string $id, string $state)
    {
        $this->id    = $id;
        $this->state = $state;
    }

    /**
     * Возвращает идентификатор
     *
     * @param  object $object
     * @return string
     */
    public function getId(object $object): string
    {
        return $this->getValue($object, $this->id);
    }

    /**
     * Возвращает состояние
     *
     * @param  object $object
     * @return string
     */
    public function getState(object $object): string
    {
        return $this->getValue($object, $this->state);
    }

    /**
     * Устанавливат состояние
     *
     * @param  object $object
     * @param  string $state
     * @return void
     */
    public function setState(object $object, string $state)
    {
        $this->setValue($object, $this->state, $state);
    }

    /**
     * Возвращает значение заданного свойства
     *
     * @param  object $object
     * @param  string $property
     * @return string
     */
    private function getValue(object $object, string $property): string
    {
        $method = static::ACCESSOR . ucfirst($property);
        return $object->$method();
    }

    /**
     * Устанавливает значение заданного свойства
     *
     * @param  object $object
     * @param  string $property
     * @param  string $value
     * @return void
     */
    private function setValue(object $object, string $property, string $value)
    {
        $method = static::MODIFIER . ucfirst($property);
        $object->$method($value);
    }
}
