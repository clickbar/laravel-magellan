<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;
use Countable;

class GeometryCollection extends Geometry implements Countable
{
    /**
     * @var Geometry[]
     */
    protected array $geometries;
    protected Dimension $dimension;

    public function __construct(Dimension $dimension, array $geometries)
    {
        GeometryHelper::assertValidGeometryInput(1, GeometryInterface::class, $geometries, 'geometries');
        $this->geometries = $geometries;
        $this->dimension = $dimension;
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

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
}
