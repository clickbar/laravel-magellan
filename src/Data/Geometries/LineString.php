<?php

namespace Clickbar\Magellan\Data\Geometries;

class LineString extends PointCollection
{
    /**
     * @param  \Clickbar\Magellan\Data\Geometries\Point[]  $points
     * @param  null|int  $srid
     * @return self
     */
    public static function make(array $points, ?int $srid = null): self
    {
        return new self($points, $srid);
    }
}
