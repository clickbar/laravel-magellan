<?php

namespace Clickbar\Magellan\Data\Boxes;

use Clickbar\Magellan\Data\Geometries\GeometryHelper;
use Illuminate\Database\Grammar;

class Box2D extends Box
{
    private function __construct(protected float $xMin, protected float $yMin, protected float $xMax, protected float $yMax)
    {
    }

    public static function make(float $xMin, float $yMin, float $xMax, float $yMax): self
    {
        return new self($xMin, $yMin, $xMax, $yMax);
    }

    public function getXMin(): float
    {
        return $this->xMin;
    }

    public function getYMin(): float
    {
        return $this->yMin;
    }

    public function getXMax(): float
    {
        return $this->xMax;
    }

    public function getYMax(): float
    {
        return $this->yMax;
    }

    public function toString(): string
    {
        $min = GeometryHelper::stringifyFloat($this->xMin).' '.GeometryHelper::stringifyFloat($this->yMin);
        $max = GeometryHelper::stringifyFloat($this->xMax).' '.GeometryHelper::stringifyFloat($this->yMax);

        return "BOX({$min},{$max})";
    }

    public function getValue(Grammar $grammar): string
    {
        return $grammar->quoteString($this->toString()).'::box2d';
    }
}
