<?php

namespace Clickbar\Magellan\Data\Boxes;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;

abstract class Box implements ExpressionContract
{
    abstract public function toString(): string;
}
