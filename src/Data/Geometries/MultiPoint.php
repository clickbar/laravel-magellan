<?php

namespace Clickbar\Magellan\Data\Geometries;

class MultiPoint extends PointCollection
{
    /**
     * @param  Point[]  $points
     * @param  int|null  $srid
     * @return self
     */
    public static function make(array $points, ?int $srid = null): self
    {
        return new self($points, $srid);
    }
}
