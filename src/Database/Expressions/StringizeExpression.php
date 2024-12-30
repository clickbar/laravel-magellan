<?php

namespace Clickbar\Magellan\Database\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;

trait StringizeExpression
{
    protected function stringize(Grammar $grammar, string|Expression $expression): float|int|string
    {
        return match ($grammar->isExpression($expression)) {
            true => $grammar->getValue($expression),
            false => $grammar->wrap($expression),
        };
    }
}
