<?php

namespace Clickbar\Magellan\Data\Features;

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\IO\FeatureModelFactory;

class FeatureFactory implements FeatureModelFactory
{
    public function createFeatureCollection(Dimension $dimension, ?int $srid, array $features): FeatureCollection
    {
        return FeatureCollection::make($features, $srid, $dimension);
    }
}
