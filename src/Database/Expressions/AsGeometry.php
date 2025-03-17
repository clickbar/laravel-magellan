<?php

namespace Clickbar\Magellan\Database\Expressions;

use Closure;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Grammar;

class AsGeometry extends GeometryWrapperExpression
{
    public function __construct(
        public string|ExpressionContract|Closure $expression,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringifyQueryParameter($grammar, $this->expression);

        return "($expression)::geometry";
    }
}
