<?php

namespace Clickbar\Magellan\Database\Expressions;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Grammar;

class Aliased implements ExpressionContract
{
    use StringizeExpression;

    public function __construct(
        public string|ExpressionContract $expression,
        public string $as,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);
        $wrappedAs = $grammar->wrap($this->as);

        return "$expression AS $wrappedAs";
    }
}
