<?php

namespace Clickbar\Magellan\Data\Geometries;

class Polygon extends MultiLineString
{
    /**
     * @param  LineString[]  $lineStrings
     * @param  int|null  $srid
     * @return self
     */
    public static function make(array $lineStrings, ?int $srid = null): self
    {
        return new self($lineStrings, $srid);
    }
}
