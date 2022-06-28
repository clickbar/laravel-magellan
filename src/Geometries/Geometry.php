<?php

namespace Clickbar\Postgis\Geometries;

use JsonSerializable;

abstract class Geometry implements GeometryInterface, JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        $generatorClass = config('postgis.json_generator');
        $generator = new $generatorClass();

        return json_encode($generator->generate($this));
    }
}
