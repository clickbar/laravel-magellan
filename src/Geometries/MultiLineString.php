<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;
use Countable;

class MultiLineString implements Countable, GeometryInterface
{
    /**
     * @var LineString[]
     */
    protected array $lineStrings;

    protected Dimension $dimension;

    /**
     * @param LineString[] $lienStrings
     */
    public function __construct(Dimension $dimension, array $lienStrings)
    {
        GeometryHelper::assertValidGeometryInput(1, LineString::class, $lienStrings, 'lineStrings');
        $this->lineStrings = $lienStrings;
        $this->dimension = $dimension;
    }

    /**
     * @return LineString[]
     */
    public function getLineStrings(): array
    {
        return $this->lineStrings;
    }

    public function count(): int
    {
        return count($this->lineStrings);
    }

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
}
