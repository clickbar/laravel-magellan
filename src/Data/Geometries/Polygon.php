<?php

namespace Clickbar\Magellan\Data\Geometries;

use Clickbar\Magellan\Cast\GeometryCast;

class Polygon extends MultiLineString
{
    /**
     * @param  LineString[]  $lineStrings
     */
    public static function make(array $lineStrings, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($lineStrings, $srid, $dimension);
    }

    /** @return GeometryCast<Polygon> */
    public static function castUsing(array $arguments): GeometryCast
    {
        return new GeometryCast(Polygon::class);
    }
}
