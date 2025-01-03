<?php

namespace Clickbar\Magellan\Data\Boxes;

use Clickbar\Magellan\Cast\BBoxCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use JsonSerializable;
use Stringable;

abstract class Box implements Castable, ExpressionContract, Stringable, JsonSerializable
{
    abstract public static function fromString(string $box): self;

    abstract public function toSqlString(): string;

    /**
     * @return BBoxCast<Box>
     */
    public static function castUsing(array $arguments): BBoxCast
    {
        return new BBoxCast(static::class);
    }
}
