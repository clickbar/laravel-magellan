<?php

namespace Clickbar\Magellan\Eloquent\Builder;

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
