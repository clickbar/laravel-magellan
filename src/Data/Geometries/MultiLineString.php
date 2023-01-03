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
     * @param  int|null  $srid
     * @return self
     */
    public static function make(array $lineStrings, ?int $srid = null): self
    {
        return new self($lineStrings, $srid);
    }

    protected function __construct(array $lineStrings, ?int $srid = null)
    {
        parent::__construct($srid);

        GeometryHelper::assertValidGeometryInput(0, LineString::class, $lineStrings, 'lineStrings');
        $this->lineStrings = $lineStrings;
    }

    public function getDimension(): Dimension
    {
        return $this->lineStrings[0]->getDimension();
    }

    public function getSrid(): ?int
    {
        if (empty($this->lineStrings)) {
            return parent::getSrid();
        }

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
