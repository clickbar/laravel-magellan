<?php

namespace Clickbar\Postgis\IO\Parser\WKB;

use Clickbar\Postgis\GeometriesOld\Geometry;
use Clickbar\Postgis\GeometriesOld\GeometryInterface;
use Clickbar\Postgis\IO\Coordinate;
use Clickbar\Postgis\IO\Dimension;
use Clickbar\Postgis\IO\Parser\BaseParser;
use RuntimeException;

class WKBParser extends BaseParser
{
    public const MASK_SRID = 0x20000000;
    public const MASK_Z = 0x80000000;
    public const MASK_M = 0x40000000;

    private Scanner $scanner;

    private Dimension $dimension;
    private int $srid;

    public function parse($input): GeometryInterface
    {
        $this->scanner = new Scanner($input);

        return $this->parseWkbSegment();
    }

    protected function parseWkbSegment(?WKBGeometryType $expectedGeometryType = null): GeometryInterface
    {
        $this->setupByteOrder();

        $geometryTypeIntegerValue = $this->scanner->integer();

        $srid = null;
        $hasZ = false;
        $hasM = false;
        $wkb12 = false;

        if ($geometryTypeIntegerValue & self::MASK_SRID) {
            $srid = $this->scanner->integer();
            $geometryTypeIntegerValue = ($geometryTypeIntegerValue & ~self::MASK_SRID);
        }

        if ($geometryTypeIntegerValue & self::MASK_Z) {
            $hasZ = true;
            $geometryTypeIntegerValue = ($geometryTypeIntegerValue & ~self::MASK_Z);
        }

        if ($geometryTypeIntegerValue & self::MASK_M) {
            $hasM = true;
            $geometryTypeIntegerValue = ($geometryTypeIntegerValue & ~self::MASK_M);
        }

        if (((int)($geometryTypeIntegerValue / 1000)) & 1) {
            $hasZ = true;
            $wkb12 = true;
        }

        if (((int)($geometryTypeIntegerValue / 1000)) & 2) {
            $hasM = true;
            $wkb12 = true;
        }

        if ($wkb12) {
            $geometryTypeIntegerValue %= 1000;
        }

        $dimension = $this->getDimension($hasZ, $hasM);
        $geometryType = WKBGeometryType::from($geometryTypeIntegerValue);

        $this->assertExpectedGeometryType($expectedGeometryType, $geometryType);
        $this->assertSameSrid($this->srid, $srid);
        $this->assertSameDimension($this->dimension, $dimension);

        if ($srid) {
            $this->srid = $srid;
        }

        $this->dimension = $dimension;

        return $this->parseGeometry($geometryType);
    }

    protected function parseGeometry(WKBGeometryType $geometryType): GeometryInterface
    {
        return match ($geometryType) {
            WKBGeometryType::point => $this->point(),
            WKBGeometryType::lineString => $this->lineString(),
            WKBGeometryType::polygon => $this->polygon(),
            WKBGeometryType::multiPoint => $this->multiPoint(),
            WKBGeometryType::multiLineString => $this->multiLineString(),
            WKBGeometryType::multiPolygon => $this->multiPolygon(),
            default => $this->geometryCollection(),
        };
    }

    protected function getDimension(bool $hasZ, bool $hasM): Dimension
    {
        if ($hasZ && $hasM) {
            return Dimension::DIMENSION_4D;
        } elseif ($hasM) {
            return Dimension::DIMENSION_3DM;
        } elseif ($hasZ) {
            return Dimension::DIMENSION_3DZ;
        }

        return Dimension::DIMENSION_2D;
    }

    protected function setupByteOrder()
    {
        $endianValue = $this->scanner->byte();

        $byteOrder = match ($endianValue) {
            0 => ByteOrder::bigEndian,
            1 => ByteOrder::littleEndian,
            default => throw new RuntimeException(sprintf('Bad endian byte value %s.', json_encode($endianValue))),
        };

        $this->scanner->setByteOrder($byteOrder);
    }


    // ************************************************ Geometry Helper ***************************************************

    protected function geometryCollection(): GeometryInterface
    {
        $num = $this->scanner->integer();
        $geometries = [];
        for ($i = 0; $i < $num; $i++) {
            $geometries[] = $this->parseWkbSegment();
        }

        return $this->factory->createGeometryCollection(
            $this->dimension,
            $this->srid,
            $geometries,
        );
    }

    protected function multiPolygon(): GeometryInterface
    {
        $num = $this->scanner->integer();
        $polygons = [];
        for ($i = 0; $i < $num; $i++) {
            $polygons[] = $this->parseWkbSegment(WKBGeometryType::polygon);
        }

        return $this->factory->createMultiPolygon(
            $this->dimension,
            $this->srid,
            $polygons,
        );
    }

    protected function multiLineString(): GeometryInterface
    {
        $num = $this->scanner->integer();
        $lineStrings = [];
        for ($i = 0; $i < $num; $i++) {
            $lineStrings[] = $this->parseWkbSegment(WKBGeometryType::lineString);
        }

        return $this->factory->createMultiLineString(
            $this->dimension,
            $this->srid,
            $lineStrings,
        );
    }

    protected function multiPoint(): GeometryInterface
    {
        $num = $this->scanner->integer();
        $points = [];
        for ($i = 0; $i < $num; $i++) {
            $points[] = $this->parseWkbSegment(WKBGeometryType::point);
        }

        return $this->factory->createMultiPoint(
            $this->dimension,
            $this->srid,
            $points,
        );
    }

    protected function polygon(): GeometryInterface
    {
        $num = $this->scanner->integer();
        $linearRings = [];
        for ($i = 0; $i < $num; $i++) {
            $linearRings[] = $this->lineString(true);
        }

        return $this->factory->createPolygon(
            $this->dimension,
            $this->srid,
            $linearRings,
        );
    }

    protected function lineString($isLinearRing = false): GeometryInterface
    {
        $num = $this->scanner->integer();

        $points = [];
        for ($i = 0; $i < $num; $i++) {
            $points[] = $this->point();
        }

        if ($isLinearRing) {
            return $this->factory->createLinearRing(
                $this->dimension,
                $this->srid,
                $points,
            );
        }

        return $this->factory->createLineString(
            $this->dimension,
            $this->srid,
            $points,
        );
    }

    protected function point(): Geometry
    {
        $coordinate = new Coordinate(
            $this->scanner->double(),
            $this->scanner->double(),
        );

        if ($this->dimension->has3Dimensions()) {
            $coordinate->setZ($this->scanner->double());
        }

        if ($this->dimension->isMeasured()) {
            $coordinate->setM($this->scanner->double());
        }

        return $this->factory->createPoint(
            $this->dimension,
            $this->srid,
            $coordinate,
        );
    }

    // ************************************************ Assertions ***************************************************

    protected function assertExpectedGeometryType(?WKBGeometryType $expected, WKBGeometryType $actual)
    {
        if ($expected && $expected !== $actual) {
            throw new RuntimeException(sprintf(
                'Unexpected geometry type %s, expected %s.',
                json_encode($actual),
                json_encode($expected)
            ));
        }
    }

    protected function assertSameSrid(?int $expected, ?int $actual)
    {
        if ($actual != null && $expected != null && $expected !== $actual) {
            throw new RuntimeException(sprintf(
                'SRID mismatch between %s and expected %s.',
                json_encode($actual),
                json_encode($expected)
            ));
        }
    }

    protected function assertSameDimension(?Dimension $expected, Dimension $actual)
    {
        if ($expected && $expected !== $actual) {
            throw new RuntimeException(sprintf(
                'Dimension mismatch between %s and expected %s.',
                json_encode($actual),
                json_encode($expected)
            ));
        }
    }
}
