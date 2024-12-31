<?php

namespace Clickbar\Magellan\Database\Expressions;

use Clickbar\Magellan\Database\Builder\PreparesValue;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;

abstract class GeometryWrapperExpression implements Expression
{
    use PreparesValue;

    public function stringitize(Grammar $grammar, mixed $value): string
    {
        return $this->prepareParam($grammar, $value);
    }
}
