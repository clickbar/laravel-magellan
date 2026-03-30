<?php

namespace Clickbar\Magellan\Data\Boxes;

use Clickbar\Magellan\Cast\BBoxCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use JsonSerializable;
use Stringable;

abstract class Box implements Castable, ExpressionContract, JsonSerializable, Stringable
{
    protected ?int $srid = null;

    abstract public static function fromString(string $box, ?int $srid = null): self;

    /**
     * @return BBoxCast<Box>
     */
    public static function castUsing(array $arguments): BBoxCast
    {
        $srid = $arguments[0] ?? null;
        return new BBoxCast(static::class, $srid);
    }

    abstract public function toRawSql(): string;

    public function getSrid(): ?int
    {
        return $this->srid;
    }

    public function __toString(): string
    {
        return $this->toRawSql();
    }
}
