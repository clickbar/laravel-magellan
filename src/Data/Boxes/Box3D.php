<?php

namespace Clickbar\Magellan\Data\Boxes;

use Illuminate\Database\Query\Expression;

class Box3D extends Box
{
    private function __construct(protected float $xMin, protected float $yMin, protected float $zMin, protected float $xMax, protected float $yMax, protected float $zMax)
    {
    }

    public static function make(float $xMin, float $yMin, float $zMin, float $xMax, float $yMax, float $zMax): self
    {
        return new self($xMin, $yMin, $zMin, $xMax, $yMax, $zMax);
    }

    public function getXMin(): float
    {
        return $this->xMin;
    }

    public function getYMin(): float
    {
        return $this->yMin;
    }

    public function getZMin(): float
    {
        return $this->zMin;
    }

    public function getXMax(): float
    {
        return $this->xMax;
    }

    public function getYMax(): float
    {
        return $this->yMax;
    }

    public function getZMax(): float
    {
        return $this->zMax;
    }

    public function toString(): string
    {
        return "BOX3D({$this->xMin} {$this->yMin} {$this->zMin},{$this->xMax} {$this->yMax} {$this->zMax})";
    }

    public function toExpression(): Expression
    {
        return new Expression("'{$this->toString()}'::box3d");
    }
}
