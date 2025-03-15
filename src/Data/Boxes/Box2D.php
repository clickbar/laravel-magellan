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

    public function toRawSql(): string
    {
        $min = GeometryHelper::stringifyFloat($this->xMin).' '.GeometryHelper::stringifyFloat($this->yMin);
        $max = GeometryHelper::stringifyFloat($this->xMax).' '.GeometryHelper::stringifyFloat($this->yMax);

        return "BOX({$min},{$max})";
    }

    public function getValue(Grammar $grammar): string
    {
        return $grammar->quoteString($this->toRawSql()).'::box2d';
    }

    public static function fromString(string $box): self
    {
        preg_match('/^BOX\(([-+]?\d+(?:.\d+)?)\s([-+]?\d+(?:.\d+)?),([-+]?\d+(?:.\d+)?)\s([-+]?\d+(?:.\d+)?)\)$/i', $box, $coordinates);

        if (count($coordinates) !== 5) {
            throw new \InvalidArgumentException('Invalid format for Box2D. Expected BOX(x y,x y), got '.$box);
        }

        return new self(
            floatval($coordinates[1]),
            floatval($coordinates[2]),
            floatval($coordinates[3]),
            floatval($coordinates[4])
        );
    }

    /**
     * @return array{float, float, float, float}
     */
    public function jsonSerialize(): array
    {
        return [$this->xMin, $this->yMin, $this->xMax, $this->yMax];
    }
}
