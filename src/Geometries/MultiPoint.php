<?php

namespace Clickbar\Magellan\Geometries;

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
