<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;
use Countable;

class MultiLineString extends Geometry implements Countable
{
    /**
     * @var LineString[]
     */
    protected array $lineStrings;

    /**
     * @param LineString[] $lienStrings
     */
    public function __construct(Dimension $dimension, array $lienStrings, ?int $srid = null)
    {
        GeometryHelper::assertValidGeometryInput(1, LineString::class, $lienStrings, 'lineStrings');
        parent::__construct($dimension, $srid);
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
}
