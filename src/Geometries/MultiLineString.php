<?php

namespace Clickbar\Magellan\Geometries;

use Clickbar\Magellan\IO\Dimension;
use Countable;

class MultiLineString extends Geometry implements Countable
{
    /**
     * @var LineString[]
     */
    protected array $lineStrings;

    /**
     * @param LineString[] $lineStrings
     * @return static
     */
    public static function make(array $lineStrings): self
    {
        return new self($lineStrings);
    }

    /**
     * @param LineString[] $lienStrings
     */
    protected function __construct(array $lienStrings)
    {
        GeometryHelper::assertValidGeometryInput(1, LineString::class, $lienStrings, 'lineStrings');
        $this->lineStrings = $lienStrings;
    }

    public function getDimension(): Dimension
    {
        return $this->lineStrings[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        return $this->lineStrings[0]->getSrid();
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
