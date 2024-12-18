<?php

namespace Clickbar\Magellan\Data\Geometries;

use Countable;

class MultiLineString extends Geometry implements Countable
{
    /**
     * @var LineString[]
     */
    protected array $lineStrings;

    /**
     * @param  LineString[]  $lineStrings
     */
    public static function make(array $lineStrings, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        return new self($lineStrings, $srid, $dimension);
    }

    protected function __construct(array $lineStrings, ?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D)
    {
        parent::__construct($srid, $dimension);

        GeometryHelper::assertValidGeometryInput(0, LineString::class, $lineStrings, 'lineStrings');
        $this->lineStrings = $lineStrings;
    }

    public function getDimension(): Dimension
    {
        if ($this->isEmpty()) {
            return parent::getDimension();
        }

        return $this->lineStrings[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        if ($this->isEmpty()) {
            return parent::getSrid();
        }

        return $this->lineStrings[0]->getSrid();
    }

    public function isEmpty(): bool
    {
        return empty($this->lineStrings);
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
