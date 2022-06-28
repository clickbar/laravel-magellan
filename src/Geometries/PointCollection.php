<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;
use InvalidArgumentException;

abstract class PointCollection extends Geometry
{
    /**
     * @var Point[]
     */
    protected array $points;

    protected Dimension $dimension;

    /**
     * @param Point[] $points
     */
    public function __construct(Dimension $dimension, array $points)
    {
        GeometryHelper::assertValidGeometryInput(2, Point::class, $points, 'points');
        $this->points = $points;
        $this->dimension = $dimension;
    }

    /**
     * @return Point[] $points
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    public function prependPoint(Point $point)
    {
        array_unshift($this->points, $point);
    }

    public function appendPoint(Point $point)
    {
        $this->points[] = $point;
    }

    public function insertPoint($index, Point $point)
    {
        if (count($this->points) - 1 < $index) {
            throw new InvalidArgumentException('$index is greater than the size of the array');
        }

        array_splice($this->points, $index, 0, [$point]);
    }

    public function count(): int
    {
        return count($this->points);
    }

    /**
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
}
