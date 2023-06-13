<?php

namespace Clickbar\Magellan\IO;

use Clickbar\Magellan\Data\Features\FeatureCollection;
use Clickbar\Magellan\Data\Geometries\Dimension;

interface FeatureModelFactory
{
    /**
     * TODO docblock
     */
    public function createFeatureCollection(
        Dimension $dimension,
        ?int $srid,
        array $features,
    ): FeatureCollection;
}
