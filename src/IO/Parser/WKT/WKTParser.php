<?php

namespace Clickbar\Magellan\IO\Parser\WKT;

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\GeometryCollection;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiLineString;
use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Exception\UnknownWKTTypeException;
use Clickbar\Magellan\IO\Coordinate;
use Clickbar\Magellan\IO\Parser\BaseParser;
use Illuminate\Support\Str;

class WKTParser extends BaseParser
{
    protected ?Dimension $dimension = null;

    protected ?int $srid = null;

    public function parse($input): Geometry
    {
        $input = $this->parseAndRemoveSrid($input);

        $wktType = $this->getWKTType($input);

        $method = 'parse'.$this->getParseMethodName($wktType);

        return $this->$method($this->getWKTArgument($wktType, $input));
    }

    private function parseAndRemoveSrid(string $input): string
    {
        if (Str::startsWith($input, 'SRID')) {
            $this->srid = (int) Str::after($input, '=');
            $input = Str::after($input, ';');
        }

        return $input;
    }

    /**
     * Returns a WKT argument text without the type tag and the parentheses.
     * The argument can either be "EMPTY" or text wrapped in parentheses.
     * For EMPTY, null is returned. For text wrapped in parentheses, the text is returned as a string.
     */
    private function getWKTArgument(string $wktType, string $input): ?string
    {
        $value = Str::of($input)
            ->after($wktType)
            ->trim()
            ->toString();

        if ($value === 'EMPTY') {
            return null;
        }

        return Str::between($value, '(', ')');
    }

    /**
     * Returns the WKT type of the given WKT string.
     */
    private function getWKTType(string $value): string
    {
        return Str::of($value)
            ->before('(')
            ->before('EMPTY')
            ->trim()
            ->toString();
    }

    /**
     * Returns the method name of the method being responsible for parsing the specific type
     */
    private function getParseMethodName(string $wktType): string
    {
        $upperType = strtoupper($wktType);

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
            default => throw new UnknownWKTTypeException('Type was '.$wktType),
        };
    }

    private function removeCharsAndBrackets(string $string): string
    {
        $leadingRemoved = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($string));

        return preg_replace('/[a-zA-Z\(\)]+$/', '', $leadingRemoved);
    }

    public function parsePoint(?string $argument): Point
    {
        if ($argument === null) {
            return $this->factory->createPoint($this->dimension, $this->srid, null);
        }

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

    public function parseLineString(?string $argument): LineString
    {
        if ($argument === null) {
            return $this->factory->createLineString($this->dimension, $this->srid, []);
        }

        $pointArguments = explode(',', trim($argument));
        $points = array_map(fn ($pointArgument) => $this->parsePoint($pointArgument), $pointArguments);

        return $this->factory->createLineString($this->dimension, $this->srid, $points);
    }

    public function parsePolygon(?string $argument): Polygon
    {
        if ($argument === null) {
            return $this->factory->createPolygon($this->dimension, $this->srid, []);
        }

        $lineStringArguments = preg_split('/\)\s*,\s*\(/', substr(trim($argument), 1, -1));
        $lineStrings = array_map(fn ($lineStringArgument) => $this->parseLineString($lineStringArgument), $lineStringArguments);

        return $this->factory->createPolygon($this->dimension, $this->srid, $lineStrings);
    }

    public function parseMultiPoint(?string $argument): MultiPoint
    {
        if ($argument === null) {
            return $this->factory->createMultiPoint($this->dimension, $this->srid, []);
        }

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

    private function parseMultiLineString(?string $argument): MultiLineString
    {
        if ($argument === null) {
            return $this->factory->createMultiLineString($this->dimension, $this->srid, []);
        }

        $lineStringArguments = preg_split('/\)\s*,\s*\(/', substr(trim($argument), 1, -1));
        $lineStrings = array_map(fn ($lineStringArgument) => $this->parseLineString($lineStringArgument), $lineStringArguments);

        return $this->factory->createMultiLineString($this->dimension, $this->srid, $lineStrings);
    }

    private function parseMultiPolygon(?string $argument): MultiPolygon
    {
        if ($argument === null) {
            return $this->factory->createMultiPolygon($this->dimension, $this->srid, []);
        }

        $parts = preg_split('/(\)\s*\)\s*,\s*\(\s*\()/', $argument, -1, PREG_SPLIT_DELIM_CAPTURE);
        $polygonArguments = $this->assembleParts($parts);

        $polygons = (array_map(fn ($polygonArgument) => $this->parsePolygon($polygonArgument), $polygonArguments));

        return $this->factory->createMultiPolygon($this->dimension, $this->srid, $polygons);
    }

    private function parseGeometryCollection(?string $argument): GeometryCollection
    {
        if ($argument === null) {
            return $this->factory->createGeometryCollection($this->dimension, $this->srid, []);
        }

        $geometryWktSegments = preg_split('/,\s*(?=[A-Za-z])/', $argument);
        $geometries = array_map(
            function ($geometryWktSegment) {
                return $this->parse($geometryWktSegment);
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
