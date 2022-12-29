<?php

namespace Clickbar\Magellan\Data\Boxes;

class Box2D extends Box
{
    private function __construct(protected float $xMin, protected float $yMin, protected float $xMax, protected float $yMax)
    {
    }

    public static function make(float $xMin, float $yMin, float $xMax, float $yMax): self
    {
        return new self($xMin, $yMin, $xMax, $yMax);
    }

    /**
     * @return float
     */
    public function getXMin(): float
    {
        return $this->xMin;
    }

    /**
     * @return float
     */
    public function getYMin(): float
    {
        return $this->yMin;
    }

    /**
     * @return float
     */
    public function getXMax(): float
    {
        return $this->xMax;
    }

    /**
     * @return float
     */
    public function getYMax(): float
    {
        return $this->yMax;
    }

    public function toString(): string
    {
        return "BOX({$this->xMin} {$this->yMin},{$this->xMax} {$this->yMax})";
    }
}
