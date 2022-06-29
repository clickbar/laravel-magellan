<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;
use JsonSerializable;

abstract class Geometry implements GeometryInterface, JsonSerializable
{
    public function __construct(
        protected Dimension $dimension,
        protected ?int      $srid = null
    ) {
    }

    /**
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    /**
     * @return int|null
     */
    public function getSrid(): ?int
    {
        return $this->srid;
    }

    public function jsonSerialize(): mixed
    {
        $generatorClass = config('postgis.json_generator');
        $generator = new $generatorClass();

        return json_encode($generator->generate($this));
    }
}
