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
     */
    public static function make(array $geometries, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($geometries, $srid, $dimension);
    }

    protected function __construct(array $geometries, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D)
    {
        parent::__construct($srid, $dimension);

        GeometryHelper::assertValidGeometryInput(0, Geometry::class, $geometries, 'geometries');
        $this->geometries = $geometries;
    }

    public function getDimension(): Dimension
    {
        if ($this->isEmpty()) {
            return parent::getDimension();
        }

        return $this->geometries[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        if ($this->isEmpty()) {
            return parent::getSrid();
        }

        return $this->geometries[0]->getSrid();
    }

    public function isEmpty(): bool
    {
        return empty($this->geometries);
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
