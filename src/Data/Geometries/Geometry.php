<?php

namespace Clickbar\Magellan\Data\Geometries;

use Illuminate\Support\Facades\Config;
use JsonSerializable;

abstract class Geometry implements \Stringable, GeometryInterface, JsonSerializable
{
    public function __construct(
        protected ?int $srid = null,
        protected Dimension $dimension = Dimension::DIMENSION_2D,
    ) {
    }

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    abstract public function isEmpty(): bool;

    public function getSrid(): ?int
    {
        return $this->srid;
    }

    public function hasSrid(): bool
    {
        return $this->getSrid() !== null && $this->getSrid() !== 0;
    }

    public function jsonSerialize(): mixed
    {
        $generatorClass = Config::get('magellan.json_generator');
        $generator = new $generatorClass();

        return $generator->generate($this);
    }

    public function __toString(): string
    {
        $generatorClass = Config::get('magellan.string_generator');
        $generator = new $generatorClass();

        $generated = $generator->generate($this);
        if (! is_string($generated)) {
            return json_encode($generated);
        }

        return $generated;
    }
}
