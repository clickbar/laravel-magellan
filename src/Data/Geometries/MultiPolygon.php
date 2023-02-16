<?php

namespace Clickbar\Magellan\Data\Geometries;

class MultiPolygon extends Geometry implements \Countable
{
    /**
     * @var Polygon[]
     */
    protected array $polygons;

    /**
     * @param  Polygon[]  $polygons
     */
    public static function make(array $polygons, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($polygons, $srid, $dimension);
    }

    /**
     * @param  Polygon[]  $polygons
     */
    protected function __construct(array $polygons, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D)
    {
        parent::__construct($srid, $dimension);

        GeometryHelper::assertValidGeometryInput(0, Polygon::class, $polygons, 'polygons');
        $this->polygons = $polygons;
    }

    public function getDimension(): Dimension
    {
        if ($this->isEmpty()) {
            return parent::getDimension();
        }

        return $this->polygons[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        if ($this->isEmpty()) {
            return parent::getSrid();
        }

        return $this->polygons[0]->getSrid();
    }

    public function isEmpty(): bool
    {
        return empty($this->polygons);
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
