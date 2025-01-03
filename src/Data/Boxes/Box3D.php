<?php

namespace Clickbar\Magellan\Data\Boxes;

use Clickbar\Magellan\Data\Geometries\GeometryHelper;
use Illuminate\Database\Grammar;

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
        $min = GeometryHelper::stringifyFloat($this->xMin).' '.GeometryHelper::stringifyFloat($this->yMin).' '.GeometryHelper::stringifyFloat($this->zMin);
        $max = GeometryHelper::stringifyFloat($this->xMax).' '.GeometryHelper::stringifyFloat($this->yMax).' '.GeometryHelper::stringifyFloat($this->zMax);

        return "BOX3D({$min},{$max})";
    }

    public function getValue(Grammar $grammar): string
    {
        return $grammar->quoteString($this->toString()).'::box3d';
    }

    public static function fromString(string $box): self
    {
        preg_match('/^BOX3D\(([-+]?\d+(?:.\d+)?)\s([-+]?\d+(?:.\d+)?)\s([-+]?\d+(?:.\d+)?),([-+]?\d+(?:.\d+)?)\s([-+]?\d+(?:.\d+)?)\s([-+]?\d+(?:.\d+)?)\)$/i', $box, $coordinates);

        if (count($coordinates) !== 7) {
            throw new \InvalidArgumentException('Invalid format for Box3D. Expected BOX3D(x y z,x y z), got '.$box);
        }

        return new self(
            floatval($coordinates[1]),
            floatval($coordinates[2]),
            floatval($coordinates[3]),
            floatval($coordinates[4]),
            floatval($coordinates[5]),
            floatval($coordinates[6])
        );
    }
}
