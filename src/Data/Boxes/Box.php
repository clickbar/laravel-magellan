<?php

namespace Clickbar\Magellan\Data\Boxes;

use Illuminate\Database\Query\Expression;

abstract class Box
{
    abstract public function toString(): string;

    abstract public function toExpression(): Expression;
}
