<?php

namespace Clickbar\Postgis\Geometries;

use InvalidArgumentException;

class GeometryHelper
{
    public static function assertValidGeometryInput(int $minCount, string $class, array $input, string $variableName)
    {
        if (count($input) < $minCount) {
            throw new InvalidArgumentException("$$variableName must contain at least $minCount entry/entries.");
        }

        $validated = array_filter($input, fn ($x) => $x instanceof $class);

        if (count($validated) !== count($input)) {
            throw new InvalidArgumentException("$$variableName must be an array of $class");
        }
    }
}
