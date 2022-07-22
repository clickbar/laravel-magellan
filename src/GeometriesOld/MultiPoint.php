<?php

namespace Clickbar\Magellan\GeometriesOld;

use InvalidArgumentException;

class MultiPoint extends PointCollection implements GeometryInterface, \JsonSerializable
{
    /**
     * @param Point[] $points
     */
    public function __construct(array $points)
    {
        if (count($points) < 1) {
            throw new InvalidArgumentException('$points must contain at least one entry');
        }
        $validated = array_filter($points, function ($value) {
            return $value instanceof Point;
        });
        if (count($points) !== count($validated)) {
            throw new InvalidArgumentException('$points must be an array of Points');
        }
        $this->points = $points;
    }

    public function is3d()
    {
        if (count($this->points) === 0) {
            return false;
        }

        return $this->points[0]->is3d();
    }

    public function toWKT()
    {
        $wktType = 'MULTIPOINT';
        if ($this->is3d()) {
            $wktType .= ' Z';
        }

        return sprintf('%s(%s)', $wktType, (string)$this);
    }

    public static function fromWKT($wkt)
    {
        $wktArgument = Geometry::getWKTArgument($wkt);

        return static::fromString($wktArgument);
    }

    public static function fromString($wktArgument)
    {
        if (! strpos(trim($wktArgument), '(')) {
            $points = explode(',', $wktArgument);
            $wktArgument = implode(',', array_map(function ($pair) {
                return '(' . trim($pair) . ')';
            }, $points));
        };

        $matches = [];
        preg_match_all('/\(\s*([+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)+\s+[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)+(\s+[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)+)?)\s*\)/', trim($wktArgument), $matches);

        if (count($matches) < 2) {
            return new static([]);
        }

        $points = array_map(function ($pair) {
            return Point::fromPair($pair);
        }, $matches[1]);

        return new static($points);
    }

    public function __toString()
    {
        return implode(',', array_map(function (Point $point) {
            return sprintf('(%s)', $point->toPair());
        }, $this->points));
    }

    /**
     * Convert to GeoJson MultiPoint that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\MultiPoint
     */
    public function jsonSerialize()
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point->jsonSerialize();
        }

        return new \GeoJson\Geometry\MultiPoint($points);
    }
}
