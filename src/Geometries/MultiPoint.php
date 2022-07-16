<?php

namespace Clickbar\Postgis\Geometries;

class MultiPoint extends PointCollection
{
    /**
     * @param Point[] $points
     */
    public static function make(array $points): self
    {
        return new self($points);
    }
}
