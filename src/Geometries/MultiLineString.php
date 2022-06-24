<?php

namespace Clickbar\Postgis\Geometries;

use Countable;

class MultiLineString implements Countable, GeometryInterface
{
    /**
     * @var LineString[]
     */
    protected array $lineStrings;

    /**
     * @param LineString[] $lienStrings
     */
    public function __construct(array $lienStrings)
    {
        GeometryHelper::assertValidGeometryInput(1, LineString::class, $lienStrings, 'lineStrings');
        $this->lineStrings = $lienStrings;
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
