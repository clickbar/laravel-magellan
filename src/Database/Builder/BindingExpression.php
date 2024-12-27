<?php

namespace Clickbar\Magellan\Database\Builder;

class BindingExpression
{
    public function __construct(protected mixed $value)
    {
    }

    /**
     * @return string
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
