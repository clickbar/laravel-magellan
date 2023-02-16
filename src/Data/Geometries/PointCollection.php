<?php

namespace Clickbar\Magellan\Data\Geometries;

use InvalidArgumentException;

abstract class PointCollection extends Geometry
{
    /**
     * @var Point[]
     */
    protected array $points;

    /**
     * @param  Point[]  $points
     */
    protected function __construct(array $points, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D)
    {
        parent::__construct($srid, $dimension);

        GeometryHelper::assertValidGeometryInput(0, Point::class, $points, 'points');
        $this->points = $points;
    }

    public function getSrid(): ?int
    {
        if ($this->isEmpty()) {
            return parent::getSrid();
        }

        return $this->points[0]->getSrid();
    }

    public function getDimension(): Dimension
    {
        if ($this->isEmpty()) {
            return parent::getDimension();
        }

        return $this->points[0]->getDimension();
    }

    public function isEmpty(): bool
    {
        return empty($this->points);
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
