<?php

namespace Clickbar\Magellan\IO\Parser\WKT;

use Clickbar\Magellan\Exception\UnknownWKTTypeException;
use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\Geometries\MultiPolygon;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Coordinate;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\BaseParser;
use Illuminate\Support\Str;

class WKTParser extends BaseParser
{
    protected ?Dimension $dimension = null;

    protected ?int $srid = null;

    public function parse($input): Geometry
    {
        $input = $this->parseAndRemoveSrid($input);
        $method = 'parse'.$this->getParseMethodName($input);

        return $this->$method($this->getWKTArgument($input));
    }

    private function parseAndRemoveSrid(string $input): string
    {
        if (Str::startsWith($input, 'SRID')) {
            $this->srid = (int) Str::after($input, '=');
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
     *
     * @param  string  $value
     * @return string
     */
    private function getParseMethodName(string $value): string
    {
        $type = Str::of($value)
            ->before('(')
            ->trim();

        $upperType = strtoupper($type);

        if (Str::endsWith($upperType, 'ZM')) {
            $dimension = Dimension::DIMENSION_4D;
        } elseif (Str::endsWith($upperType, 'Z')) {
            $dimension = Dimension::DIMENSION_3DZ;
        } elseif (Str::endsWith($upperType, 'M')) {
            $dimension = Dimension::DIMENSION_3DM;
        } else {
            $dimension = Dimension::DIMENSION_2D;
        }

        $this->assertSameDimension($this->dimension, $dimension);
        $this->dimension = $dimension;

        return match ($upperType) {
            'POINT', 'POINTZ', 'POINT Z', 'POINTM', 'POINT M', 'POINTZM', 'POINT ZM' => 'Point',
            'LINESTRING', 'LINESTRINGZ', 'LINESTRING Z', 'LINESTRINGM', 'LINESTRING M', 'LINESTRINGZM', 'LINESTRING ZM' => 'LineString',
            'POLYGON', 'POLYGONZ', 'POLYGON Z', 'POLYGONM', 'POLYGON M', 'POLYGONZM', 'POLYGON ZM' => 'Polygon',
            'MULTIPOINT', 'MULTIPOINTZ', 'MULTIPOINT Z', 'MULTIPOINTM', 'MULTIPOINT M', 'MULTIPOINTZM', 'MULTIPOINT ZM' => 'MultiPoint',
            'MULTILINESTRING', 'MULTILINESTRINGZ', 'MULTILINESTRING Z', 'MULTILINESTRINGM', 'MULTILINESTRING M', 'MULTILINESTRINGZM', 'MULTILINESTRING ZM' => 'MultiLineString',
            'MULTIPOLYGON', 'MULTIPOLYGONZ', 'MULTIPOLYGON Z', 'MULTIPOLYGONM', 'MULTIPOLYGON M', 'MULTIPOLYGONZM', 'MULTIPOLYGON ZM' => 'MultiPolygon',
            'GEOMETRYCOLLECTION', 'GEOMETRYCOLLECTIONZ', 'GEOMETRYCOLLECTION Z', 'GEOMETRYCOLLECTIONM', 'GEOMETRYCOLLECTION M', 'GEOMETRYCOLLECTIONZM', 'GEOMETRYCOLLECTION ZM' => 'GeometryCollection',
            default => throw new UnknownWKTTypeException('Type was '.$type),
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
        $coordinate = new Coordinate($splits[0], $splits[1]);

        if ($this->dimension === Dimension::DIMENSION_3DZ) {
            $coordinate->setZ($splits[2]);
        } elseif ($this->dimension === Dimension::DIMENSION_3DM) {
            $coordinate->setM($splits[2]);
        } elseif ($this->dimension === Dimension::DIMENSION_4D) {
            $coordinate->setZ($splits[2]);
            $coordinate->setM($splits[3]);
        }

        return $this->factory->createPoint($this->dimension, $this->srid, $coordinate);
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
                return '('.trim($pair).')';
            }, $points));
        }

        $pointArguments = explode(',', trim($argument));
        if (count($pointArguments) < 2) {
            return $this->factory->createMultiPoint($this->dimension, $this->srid, []);
        }
        $points = array_map(fn ($pointArgument) => $this->parsePoint($pointArgument), $pointArguments);

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
                $method = 'parse'.$this->getParseMethodName($geometryWktSegment);

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
     * @param  array  $parts
     * @return array
     */
    private function assembleParts(array $parts)
    {
        $polygons = [];
        $count = count($parts);

        for ($i = 0; $i < $count; $i++) {
            if ($i % 2 !== 0) {
                [$end, $start] = explode(',', $parts[$i]);
                $polygons[$i - 1] .= $end;
                $polygons[++$i] = $start.$parts[$i];
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
