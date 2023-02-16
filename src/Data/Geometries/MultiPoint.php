<?php

namespace Clickbar\Magellan\Data\Geometries;

class MultiPoint extends PointCollection
{
    /**
     * @param  Point[]  $points
     */
    public static function make(array $points, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($points, $srid, $dimension);
    }
}
