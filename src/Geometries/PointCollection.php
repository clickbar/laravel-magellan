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

    /**
     * @param Point[] $points
     */
    public function __construct(Dimension $dimension, array $points, ?int $srid = null)
    {
        GeometryHelper::assertValidGeometryInput(2, Point::class, $points, 'points');
        parent::__construct($dimension, $srid);
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
}
