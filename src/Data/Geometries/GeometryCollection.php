<?php

namespace Clickbar\Magellan\Data\Geometries;

use Countable;

class GeometryCollection extends Geometry implements Countable
{
    /**
     * @var Geometry[]
     */
    protected array $geometries;

    /**
     * @param  Geometry[]  $geometries
     * @param  int|null  $srid
     * @return self
     */
    public static function make(array $geometries, ?int $srid = null): self
    {
        return new self($geometries, $srid);
    }

    protected function __construct(array $geometries, ?int $srid = null)
    {
        parent::__construct($srid);

        GeometryHelper::assertValidGeometryInput(0, Geometry::class, $geometries, 'geometries');
        $this->geometries = $geometries;
    }

    public function getDimension(): Dimension
    {
        if (empty($this->geometries)) {
            return Dimension::DIMENSION_2D;
        }

        return $this->geometries[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        if (empty($this->geometries)) {
            return parent::getSrid();
        }

        return $this->geometries[0]->getSrid();
    }

    /**
     * @return Geometry[]
     */
    public function getGeometries(): array
    {
        return $this->geometries;
    }

    public function count(): int
    {
        return count($this->geometries);
    }
}
