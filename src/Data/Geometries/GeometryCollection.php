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
     * @return self
     */
    public static function make(array $geometries): self
    {
        return new self($geometries);
    }

    protected function __construct(array $geometries)
    {
        GeometryHelper::assertValidGeometryInput(1, Geometry::class, $geometries, 'geometries');
        $this->geometries = $geometries;
    }

    public function getDimension(): Dimension
    {
        return $this->geometries[0]->getDimension();
    }

    public function getSrid(): ?int
    {
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
