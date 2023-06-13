<?php

namespace Clickbar\Magellan\Data\Features;

use Illuminate\Support\Facades\Config;
use JsonSerializable;

abstract class Feature implements JsonSerializable, \Stringable
{
    public function __construct()
    {
    }

    public function jsonSerialize(): mixed
    {
        $generatorClass = Config::get('magellan.json_generator');
        $generator = new $generatorClass();

        return $generator->generate($this);
    }

    public function __toString(): string
    {
        return '';
    }
}
