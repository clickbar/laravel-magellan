<?php

namespace Clickbar\Postgis\IO\Parser\WKT;

use Clickbar\Postgis\Exception\UnknownWKTTypeException;
use Clickbar\Postgis\Geometries\Geometry;
use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Coordinate;
use Clickbar\Postgis\IO\Dimension;
use Clickbar\Postgis\IO\Parser\BaseParser;
use Illuminate\Support\Str;

class WKTParser extends BaseParser
{
    protected ?Dimension $dimension = null;
    protected ?int $srid = null;

    public function parse($input): Geometry
    {
        $input = $this->parseAndRemoveSrid($input);
        $method = 'parse' . $this->getParseMethodName($input);

        return $this->$method($this->getWKTArgument($input));
    }

    private function parseAndRemoveSrid(string $input): string
    {
        if (Str::startsWith($input, 'SRID')) {
            $this->srid = (int)Str::after($input, '=');
            $input = Str::after($input, ';');
        }

        return $input;
    }

    private function parseWKTSegment(string $segment)
    {
    }

    private function getWKTArgument(string $value)
    {
        return Str::between($value, '(', ')');
    }

    /**
     * Returns the method name of the method being responsible for parsing the specific type
     * @param string $value
     * @return string
     */
    private function getParseMethodName(string $value): string
    {
        $type = Str::of($value)
            ->before('(')
            ->trim();

        return match (strtoupper($type)) {
            'POINT', 'POINTZ', 'POINT Z' => 'Point',
            'LINESTRING', 'LINESTRINGZ', 'LINESTRING Z' => 'LineString',
            'POLYGON', 'POLYGONZ', 'POLYGON Z' => 'Polygon',
            'MULTIPOINT', 'MULTIPOINTZ', 'MULTIPOINT Z' => 'MultiPoint',
            'MULTILINESTRING', 'MULTILINESTRINGZ', 'MULTILINESTRING Z' => 'MultiLineString',
            'MULTIPOLYGON', 'MULTIPOLYGONZ', 'MULTIPOLYGON Z' => 'MultiPolygon',
            'GEOMETRYCOLLECTION' => 'GeometryCollection',
            default => throw new UnknownWKTTypeException('Type was ' . $type),
        };
    }

    private function removeCharsAndBrackets(string $string): string
    {
        $leadingRemoved = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($string));

        return preg_replace('/[a-zA-Z\(\)]+$/', '', $leadingRemoved);
    }

    public function parsePoint(string $argument): Point
    {
        $pair = $this->removeCharsAndBrackets($argument);
        $splits = explode(' ', trim($pair));
        $dimension = Dimension::DIMENSION_2D;
        $coordinate = new Coordinate($splits[0], $splits[1]);

        if (count($splits) > 2) {
            $coordinate->setZ($splits[2]);
            $dimension = Dimension::DIMENSION_3DZ;
        }

        $this->assertSameDimension($this->dimension, $dimension);
        $this->dimension = $dimension;

        return $this->factory->createPoint($dimension, $this->srid, $coordinate);
    }

    public function parseLineString(string $argument): LineString
    {
        $pointArguments = explode(',', trim($argument));
        $points = array_map(fn ($pointArgument) => $this->parsePoint($pointArgument), $pointArguments);

        return $this->factory->createLineString($this->dimension, $this->srid, $points);
    }

    public function parsePolygon(string $argument): Polygon
    {
        $lineStringArguments = preg_split('/\)\s*,\s*\(/', substr(trim($argument), 1, -1));
        $lineStrings = array_map(fn ($lineStringArgument) => $this->parseLineString($lineStringArgument), $lineStringArguments);

        return $this->factory->createPolygon($this->dimension, $this->srid, $lineStrings);
    }

    public function parseMultiPoint(string $argument): MultiPoint
    {
        if (! strpos(trim($argument), '(')) {
            $points = explode(',', $argument);
            $argument = implode(',', array_map(function ($pair) {
                return '(' . trim($pair) . ')';
            }, $points));
        };

        $matches = [];
        preg_match_all('/\(\s*([+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)+\s+[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)+(\s+[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)+)?)\s*\)/', trim($argument), $matches);

        if (count($matches) < 2) {
            return $this->factory->createMultiPoint($this->dimension, $this->srid, []);
        }

        $points = array_map(fn ($pointArgument) => $this->parsePoint($pointArgument), $matches[1]);

        return $this->factory->createMultiPoint($this->dimension, $this->srid, $points);
    }

    private function parseMultiLineString(string $argument): MultiLineString
    {
        $lineStringArguments = preg_split('/\)\s*,\s*\(/', substr(trim($argument), 1, -1));
        $lineStrings = array_map(fn ($lineStringArgument) => $this->parseLineString($lineStringArgument), $lineStringArguments);

        return $this->factory->createMultiLineString($this->dimension, $this->srid, $lineStrings);
    }

    private function parseMultiPolygon(string $argument): MultiPolygon
    {
        $parts = preg_split('/(\)\s*\)\s*,\s*\(\s*\()/', $argument, -1, PREG_SPLIT_DELIM_CAPTURE);
        $polygonArguments = $this->assembleParts($parts);

        $polygons = (array_map(fn ($polygonArgument) => $this->parsePolygon($polygonArgument), $polygonArguments));

        return $this->factory->createMultiPolygon($this->dimension, $this->srid, $polygons);
    }

    private function parseGeometryCollection(string $argument): GeometryCollection
    {
        $geometryWktSegments = preg_split('/,\s*(?=[A-Za-z])/', $argument);
        $geometries = array_map(
            function ($geometryWktSegment) {
                $method = 'parse' . $this->getParseMethodName($geometryWktSegment);

                return $this->$method($this->getWKTArgument($geometryWktSegment));
            },
            $geometryWktSegments
        );

        return $this->factory->createGeometryCollection($this->dimension, $this->srid, $geometries);
    }

    /**
     * Make an array like this:
     * "((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1",
     * ")), ((",
     * "-1 -1,-1 -2,-2 -2,-2 -1,-1 -1",
     * ")), ((",
     * "-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))"
     *
     * Into:
     * "((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1))",
     * "((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))",
     * "((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))"
     *
     * @param array $parts
     * @return array
     */
    private function assembleParts(array $parts)
    {
        $polygons = [];
        $count = count($parts);

        for ($i = 0; $i < $count; $i++) {
            if ($i % 2 !== 0) {
                list($end, $start) = explode(',', $parts[$i]);
                $polygons[$i - 1] .= $end;
                $polygons[++$i] = $start . $parts[$i];
            } else {
                $polygons[] = $parts[$i];
            }
        }

        return array_values($polygons);
    }


    // ************************************************ Assertions ***************************************************

    protected function assertSameDimension(?Dimension $expected, Dimension $actual)
    {
        if ($expected && $expected !== $actual) {
            throw new \RuntimeException(sprintf(
                'Dimension mismatch between %s and expected %s.',
                json_encode($actual),
                json_encode($expected)
            ));
        }
    }
}
