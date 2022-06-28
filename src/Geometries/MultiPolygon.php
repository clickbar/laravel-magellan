<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;

class MultiPolygon implements GeometryInterface, \Countable
{
    /**
     * @var Polygon[]
     */
    protected array $polygons;

    protected Dimension $dimension;

    /**
     * @param Polygon[] $polygons
     */
    public function __construct(Dimension $dimension, array $polygons)
    {
        GeometryHelper::assertValidGeometryInput(1, Polygon::class, $polygons, 'polygons');
        $this->polygons = $polygons;
        $this->dimension = $dimension;
    }

    /**
     * @return Polygon[]
     */
    public function getPolygons(): array
    {
        return $this->polygons;
    }

    public function count(): int
    {
        return count($this->polygons);
    }

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
}
