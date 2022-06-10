<?php

namespace Clickbar\Postgis\GeometriesOld;

class LineString extends PointCollection implements GeometryInterface
{
    public function is3d()
    {
        if (count($this->points) === 0) {
            return false;
        }

        return $this->points[0]->is3d();
    }

    public function toWKT()
    {
        $wktType = 'LINESTRING';
        if ($this->is3d()) {
            $wktType .= ' Z';
        }

        return sprintf('%s(%s)', $wktType, $this->toPairList());
    }

    public static function fromWKT($wkt)
    {
        $wktArgument = Geometry::getWKTArgument($wkt);

        return static::fromString($wktArgument);
    }

    public static function fromString($wktArgument)
    {
        $pairs = explode(',', trim($wktArgument));
        $points = array_map(function ($pair) {
            return Point::fromPair($pair);
        }, $pairs);

        return new static($points);
    }

    public function __toString()
    {
        return $this->toPairList();
    }

    /**
     * Convert to GeoJson LineString that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\LineString
     */
    public function jsonSerialize()
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point->jsonSerialize();
        }

        return new \GeoJson\Geometry\LineString($points);
    }
}
