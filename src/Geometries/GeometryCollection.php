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

    public function __construct(Dimension $dimension, array $geometries, ?int $srid = null)
    {
        GeometryHelper::assertValidGeometryInput(1, GeometryInterface::class, $geometries, 'geometries');
        parent::__construct($dimension, $srid);
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
}
