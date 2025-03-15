<?php

namespace Clickbar\Magellan\Data\Boxes;

use Clickbar\Magellan\Cast\BBoxCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use JsonSerializable;
use Stringable;

abstract class Box implements Castable, ExpressionContract, JsonSerializable, Stringable
{
    abstract public static function fromString(string $box): self;

    /**
     * @return BBoxCast<Box>
     */
    public static function castUsing(array $arguments): BBoxCast
    {
        return new BBoxCast(static::class);
    }

    abstract public function toRawSql(): string;

    public function __toString(): string
    {
        return $this->toRawSql();
    }
}
