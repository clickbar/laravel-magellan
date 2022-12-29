<?php

namespace Clickbar\Magellan\Geometries;

use Illuminate\Support\Facades\Config;
use JsonSerializable;

abstract class Geometry implements GeometryInterface, JsonSerializable, \Stringable
{
    /**
     * @return Dimension
     */
    abstract public function getDimension(): Dimension;

    /**
     * @return int|null
     */
    abstract public function getSrid(): ?int;

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
