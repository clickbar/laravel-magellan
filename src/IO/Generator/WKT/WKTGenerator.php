<?php

namespace Clickbar\Magellan\IO\Generator\WKT;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\GeometryCollection;
use Clickbar\Magellan\Data\Geometries\GeometryHelper;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiLineString;
use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\BaseGenerator;

class WKTGenerator extends BaseGenerator
{
    private function generatePointCoordinateString(Point $point): string
    {
        $string = GeometryHelper::stringifyFloat($point->getX()).' '.GeometryHelper::stringifyFloat($point->getY());
        if ($point->getDimension()->hasZDimension()) {
            $string .= ' '.GeometryHelper::stringifyFloat($point->getZ());
        }
        if ($point->getDimension()->isMeasured()) {
            $string .= ' '.GeometryHelper::stringifyFloat($point->getM());
        }

        return $string;
    }

    /**
     * @param  Point[]  $points
     */
    private function generatePointsCoordinateString(array $points): string
    {
        return implode(',', array_map(fn (Point $point) => $this->generatePointCoordinateString($point), $points));
    }

    private function generateLineStringCoordinateString(LineString $lineString): string
    {
        return $this->generatePointsCoordinateString($lineString->getPoints());
    }

    private function generateMultiLineStringCoordinateString(MultiLineString $multiLineString): string
    {
        return implode(',', array_map(function (LineString $linestring) {
            return sprintf('(%s)', $this->generateLineStringCoordinateString($linestring));
        }, $multiLineString->getLineStrings()));
    }

    private function apply3dIfNeeded(string $type, Geometry $geometry): string
    {
        $dimensionLetters = '';
        if ($geometry->getDimension()->hasZDimension()) {
            $dimensionLetters .= 'Z';
        }
        if ($geometry->getDimension()->isMeasured()) {
            $dimensionLetters .= 'M';
        }

        return $type.(! empty($dimensionLetters) ? ' '.$dimensionLetters : '');
    }

    private function generateEmpty(string $type): string
    {
        return sprintf('%s EMPTY', $type);
    }

    public function generate(Geometry $geometry)
    {
        $wktWithoutSrid = parent::generate($geometry);

        if (! $geometry->hasSrid()) {
            return $wktWithoutSrid;
        }

        return sprintf('SRID=%d;%s', $geometry->getSrid(), $wktWithoutSrid);
    }

    public function generatePoint(Point $point): mixed
    {
        $wktType = $this->apply3dIfNeeded('POINT', $point);

        if ($point->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        return sprintf('%s(%s)', $wktType, $this->generatePointCoordinateString($point));
    }

    public function generateLineString(LineString $lineString): mixed
    {
        $wktType = $this->apply3dIfNeeded('LINESTRING', $lineString);

        if ($lineString->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        return sprintf('%s(%s)', $wktType, $this->generateLineStringCoordinateString($lineString));
    }

    public function generateMultiLineString(MultiLineString $multiLineString): mixed
    {
        $wktType = $this->apply3dIfNeeded('MULTILINESTRING', $multiLineString);

        if ($multiLineString->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        return sprintf('%s(%s)', $wktType, $this->generateMultiLineStringCoordinateString($multiLineString));
    }

    public function generatePolygon(Polygon $polygon): mixed
    {
        $wktType = $this->apply3dIfNeeded('POLYGON', $polygon);

        if ($polygon->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        return sprintf('%s(%s)', $wktType, $this->generateMultiLineStringCoordinateString($polygon));
    }

    public function generateMultiPolygon(MultiPolygon $multiPolygon): mixed
    {
        $wktType = $this->apply3dIfNeeded('MULTIPOLYGON', $multiPolygon);

        if ($multiPolygon->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        $polygonCoordinateStrings = implode(',', array_map(function (Polygon $polygon) {
            return sprintf('(%s)', $this->generateMultiLineStringCoordinateString($polygon));
        }, $multiPolygon->getPolygons()));

        return sprintf('%s(%s)', $wktType, $polygonCoordinateStrings);
    }

    public function generateMultiPoint(MultiPoint $multiPoint): mixed
    {
        $wktType = $this->apply3dIfNeeded('MULTIPOINT', $multiPoint);

        if ($multiPoint->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        return sprintf('%s(%s)', $wktType, $this->generatePointsCoordinateString($multiPoint->getPoints()));
    }

    public function generateGeometryCollection(GeometryCollection $geometryCollection): mixed
    {
        $geometryWktStrings = implode(',', array_map(
            function (Geometry $geometry) {
                return parent::generate($geometry);
            },
            $geometryCollection->getGeometries()
        ));

        $wktType = $this->apply3dIfNeeded('GEOMETRYCOLLECTION', $geometryCollection);

        if ($geometryCollection->isEmpty()) {
            return $this->generateEmpty($wktType);
        }

        return sprintf('%s(%s)', $wktType, $geometryWktStrings);
    }

    public function toPostgisGeometrySql(Geometry $geometry, string $schema): mixed
    {
        return sprintf("%s.ST_GeomFromEWKT('%s')", $schema, $this->generate($geometry));
    }

    public function toPostgisGeographySql(Geometry $geometry, string $schema): mixed
    {
        return sprintf("%s.ST_GeogFromText('%s')", $schema, $this->generate($geometry));
    }
}
