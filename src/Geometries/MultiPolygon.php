<?php

namespace Clickbar\Postgis\Geometries;

class MultiPolygon implements GeometryInterface, \Countable
{
    /**
     * @var Polygon[]
     */
    protected array $polygons;

    /**
     * @param Polygon[] $polygons
     */
    public function __construct(array $polygons)
    {
        GeometryHelper::assertValidGeometryInput(1, Polygon::class, $polygons, 'polygons');
        $this->polygons = $polygons;
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
}
