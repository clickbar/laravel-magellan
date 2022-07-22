<?php

namespace Clickbar\Magellan\GeometriesOld;

use Clickbar\Magellan\IO\Dimension;

interface GeometryInterface
{
    public function toWKT();

    public static function fromWKT($wkt);

    public function __toString();

    public static function fromString($wktArgument);

    public function getDimension(): Dimension;
}
