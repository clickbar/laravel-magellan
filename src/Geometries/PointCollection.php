<?php

namespace Clickbar\Magellan\Geometries;

use Clickbar\Magellan\IO\Dimension;
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
    protected function __construct(array $points)
    {
        GeometryHelper::assertValidGeometryInput(2, Point::class, $points, 'points');
        $this->points = $points;
    }

    public function getDimension(): Dimension
    {
        return $this->points[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        return $this->points[0]->getSrid();
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
