<?php

namespace Free2er\Specification;

/**
 * Обратная спецификация
 */
class ReverseSpecification implements SpecificationInterface
{
    /**
     * Исходная спецификация
     *
     * @var SpecificationInterface
     */
    private $specification;

    /**
     * Конструктор
     *
     * @param SpecificationInterface $specification
     */
    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * Возвращает исходную спецификацию
     *
     * @return SpecificationInterface
     */
    public function getSpecification(): SpecificationInterface
    {
        return $this->specification;
    }

    /**
     * Возвращает удовлетворяет ли заданное значение спецификации
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isSatisfied($value): bool
    {
        return !$this->getSpecification()->isSatisfied($value);
    }
}
