<?php

namespace Clickbar\Magellan\Data\Geometries;

class LineString extends PointCollection
{
    /**
     * @param  Point[]  $points
     * @return self
     */
    public static function make(array $points): self
    {
        return new self($points);
    }
}
