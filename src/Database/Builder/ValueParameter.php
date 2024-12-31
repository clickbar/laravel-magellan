<?php

namespace Clickbar\Magellan\Database\Builder;

class ValueParameter
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
