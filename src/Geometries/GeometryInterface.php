<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;

interface GeometryInterface
{
    public function getDimension(): Dimension;
}
