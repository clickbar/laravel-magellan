<?php

namespace Clickbar\Magellan\Data\Features;

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Countable;

class FeatureCollection extends Feature implements Countable
{
    /**
     * @var Feature[]
     */
    protected array $features;

    /**
     * @param  Feature[]  $features
     */
    public static function make(array $features, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($features, $srid, $dimension);

        // TODO determine if srid and dimension are needed at this level or contained within the Geometry
    }

    protected function __construct(array $features, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D)
    {
        parent::__construct($srid, $dimension);

        // TODO determine if srid and dimension are needed at this level or contained within the Geometry

        GeometryHelper::assertValidGeometryInput(0, Geometry::class, $features, 'geometries');
        $this->features = $features;
    }

    /**
     * @return Feature[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    public function count(): int
    {
        return count($this->features);
    }
}
