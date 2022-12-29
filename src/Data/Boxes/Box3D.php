<?php

namespace Clickbar\Magellan\Data\Boxes;

class Box3D extends Box
{
    private function __construct(protected float $xMin, protected float $yMin, protected float $zMin, protected float $xMax, protected float $yMax, protected float $zMax)
    {
    }

    public static function make(float $xMin, float $yMin, float $zMin, float $xMax, float $yMax, float $zMax): self
    {
        return new self($xMin, $yMin, $zMin, $xMax, $yMax, $zMax);
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
    public function getZMin(): float
    {
        return $this->zMin;
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

    /**
     * @return float
     */
    public function getZMax(): float
    {
        return $this->zMax;
    }

    public function toString(): string
    {
        return "BOX3D({$this->xMin} {$this->yMin} {$this->zMin},{$this->xMax} {$this->yMax} {$this->zMax})";
    }
}
