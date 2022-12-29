<?php

namespace Clickbar\Magellan\Geometries;

class MultiPolygon extends Geometry implements \Countable
{
    /**
     * @var Polygon[]
     */
    protected array $polygons;

    /**
     * @param  Polygon[]  $polygons
     * @return self
     */
    public static function make(array $polygons): self
    {
        return new self($polygons);
    }

    /**
     * @param  Polygon[]  $polygons
     */
    protected function __construct(array $polygons)
    {
        GeometryHelper::assertValidGeometryInput(1, Polygon::class, $polygons, 'polygons');
        $this->polygons = $polygons;
    }

    public function getDimension(): Dimension
    {
        return $this->polygons[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        return $this->polygons[0]->getSrid();
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
