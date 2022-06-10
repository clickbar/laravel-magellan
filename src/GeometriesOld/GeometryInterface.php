<?php

namespace Clickbar\Postgis\GeometriesOld;

interface GeometryInterface
{
    public function toWKT();

    public static function fromWKT($wkt);

    public function __toString();

    public static function fromString($wktArgument);
}
