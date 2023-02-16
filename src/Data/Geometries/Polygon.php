<?php

namespace Clickbar\Magellan\Data\Geometries;

class Polygon extends MultiLineString
{
    /**
     * @param  LineString[]  $lineStrings
     */
    public static function make(array $lineStrings, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($lineStrings, $srid, $dimension);
    }
}
