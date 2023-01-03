<?php

namespace Clickbar\Magellan\Data\Geometries;

class LineString extends PointCollection
{
    /**
     * @param  \Clickbar\Magellan\Data\Geometries\Point[]  $points
     * @param  null|int  $srid
     * @param  Dimension  $dimension
     * @return self
     */
    public static function make(array $points, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($points, $srid, $dimension);
    }
}
