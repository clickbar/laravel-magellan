<?php

namespace Clickbar\Magellan\Database\Expressions;

use Clickbar\Magellan\Database\Builder\StringifiesQueryParameters;
use Illuminate\Contracts\Database\Query\Expression;

abstract class GeometryWrapperExpression implements Expression
{
    use StringifiesQueryParameters;
}
