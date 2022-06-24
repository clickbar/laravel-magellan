<?php

namespace Clickbar\Postgis\Geometries;

use Countable;

class GeometryCollection implements GeometryInterface, Countable
{
    /**
     * @var GeometryInterface[]
     */
    protected array $geometries;

    public function __construct(array $geometries)
    {
        GeometryHelper::assertValidGeometryInput(1, GeometryInterface::class, $geometries, 'geometries');
        $this->geometries = $geometries;
    }

    /**
     * @return GeometryInterface[]
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
