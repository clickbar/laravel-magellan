<?php

namespace Clickbar\Magellan\IO\Parser;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\GeometryModelFactory;

abstract class BaseParser
{
    public function __construct(
        protected GeometryModelFactory $factory
    ) {
    }

    abstract public function parse($input): Geometry;
}
