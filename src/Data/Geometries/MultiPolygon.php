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
     * @param  int|null  $srid
     * @return self
     */
    public static function make(array $polygons, ?int $srid = null): self
    {
        return new self($polygons, $srid);
    }

    /**
     * @param  Polygon[]  $polygons
     */
    protected function __construct(array $polygons, ?int $srid = null)
    {
        parent::__construct($srid);

        GeometryHelper::assertValidGeometryInput(0, Polygon::class, $polygons, 'polygons');
        $this->polygons = $polygons;
    }

    public function getDimension(): Dimension
    {
        if (empty($this->polygons)) {
            return Dimension::DIMENSION_2D;
        }

        return $this->polygons[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        if (empty($this->polygons)) {
            return parent::getSrid();
        }

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
