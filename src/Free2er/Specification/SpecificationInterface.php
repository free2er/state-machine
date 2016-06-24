<?php

namespace Free2er\Specification;

/**
 * Интерфейс спецификации
 */
interface SpecificationInterface
{
    /**
     * Возвращает удовлетворяет ли заданное значение спецификации
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isSatisfied($value): bool;
}
