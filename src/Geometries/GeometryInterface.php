<?php

namespace Clickbar\Magellan\Geometries;

use Clickbar\Magellan\IO\Dimension;

interface GeometryInterface
{
    public function getDimension(): Dimension;
}
