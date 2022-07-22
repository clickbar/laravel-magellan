<?php

namespace Clickbar\Magellan\Geometries;

class Polygon extends MultiLineString
{
    /**
     * @param LineString[] $lineStrings
     * @return self
     */
    public static function make(array $lineStrings): self
    {
        return new self($lineStrings);
    }
}
