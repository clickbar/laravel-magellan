<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;

class MultiPolygon extends Geometry implements \Countable
{
    /**
     * @var Polygon[]
     */
    protected array $polygons;

    /**
     * @param Polygon[] $polygons
     */
    public function __construct(Dimension $dimension, array $polygons, ?int $srid = null)
    {
        GeometryHelper::assertValidGeometryInput(1, Polygon::class, $polygons, 'polygons');
        parent::__construct($dimension, $srid);
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
}
