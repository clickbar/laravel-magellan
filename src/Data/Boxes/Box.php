<?php

namespace Clickbar\Magellan\Data\Boxes;

use Clickbar\Magellan\Cast\BBoxCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;

abstract class Box implements Castable, ExpressionContract
{
    abstract public static function fromString(string $box): self;

    abstract public function toString(): string;

    /**
     * @return BBoxCast<Box>
     */
    public static function castUsing(array $arguments): BBoxCast
    {
        return new BBoxCast(static::class);
    }
}
