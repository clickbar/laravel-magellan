<?php

namespace Clickbar\Magellan\Data\Geometries;

use Clickbar\Magellan\Exception\MissingGeodeticSRIDException;

class Point extends Geometry
{
    public static function make(float $x, float $y, ?float $z = null, ?float $m = null, ?int $srid = null): self
    {
        return new self(Dimension::fromCoordinates($x, $y, $z, $m), $x, $y, $z, $m, $srid);
    }

    /**
     * Creates a point instance with the WGS84 projection (SRID=4326)
     * Points using this projection can also use the geodectic getters and setters
     */
    public static function makeGeodetic(float $latitude, float $longitude, ?float $altitude = null, ?float $m = null): self
    {
        $dimension = Dimension::fromCoordinates($longitude, $latitude, $altitude, $m);

        return new self($dimension, $longitude, $latitude, $altitude, $m, config('magellan.geodetic_default_srid'));
    }

    public static function makeEmpty(?int $srid = null, Dimension $dimension = Dimension::DIMENSION_2D): self
    {
        $z = null;
        $m = null;

        if ($dimension->isMeasured()) {
            $m = NAN;
        }
        if ($dimension->hasZDimension()) {
            $z = NAN;
        }

        return new self($dimension, NAN, NAN, $z, $m, $srid);
    }

    protected function __construct(
        Dimension $dimension,
        protected float $x,
        protected float $y,
        protected ?float $z = null,
        protected ?float $m = null,
        ?int $srid = null
    ) {
        parent::__construct($srid, $dimension);
    }

    public function isEmpty(): bool
    {
        return is_nan($this->x) && is_nan($this->y);
    }

    public function is3d(): bool
    {
        return $this->dimension->hasZDimension();
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function setX(float $x): void
    {
        $this->x = $x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setY(float $y): void
    {
        $this->y = $y;
    }

    public function getZ(): ?float
    {
        return $this->z;
    }

    public function setZ(?float $z): void
    {
        $this->z = $z;
        $this->updateDimension();
    }

    public function getM(): ?float
    {
        return $this->m;
    }

    public function setM(?float $m): void
    {
        $this->m = $m;
        $this->updateDimension();
    }

    private function updateDimension()
    {
        $this->dimension = Dimension::fromCoordinates($this->x, $this->y, $this->z, $this->m);
    }

    // **********************************************************************************
    // * GEODETIC METHODS                                                               *
    // *                                                                                *
    // * Since most Points might use lng/lat (mostly WGS84) as their coordinate system, *
    // * we provide some additional lng/lat named functions                             *
    // **********************************************************************************

    public function isGeodetic(): bool
    {
        return in_array($this->srid, config('magellan.geodetic_srids')) || $this->srid === 0;
    }

    public function getLatitude(): float
    {
        $this->assertPointIsGeodetic();

        return $this->y;
    }

    public function setLatitude(float $latitude): void
    {
        $this->assertPointIsGeodetic();
        $this->setY($latitude);
    }

    public function getLongitude(): float
    {
        $this->assertPointIsGeodetic();

        return $this->x;
    }

    public function setLongitude(float $longitude): void
    {
        $this->assertPointIsGeodetic();
        $this->setX($longitude);
    }

    public function getAltitude(): ?float
    {
        $this->assertPointIsGeodetic();

        return $this->z;
    }

    public function setAltitude(float $altitude): void
    {
        $this->assertPointIsGeodetic();
        $this->setZ($altitude);
    }

    private function assertPointIsGeodetic()
    {
        if (! $this->isGeodetic()) {
            throw new MissingGeodeticSRIDException($this->getSrid());
        }
    }
}
