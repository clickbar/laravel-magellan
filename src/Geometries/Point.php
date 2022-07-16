<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Dimension;

class Point extends Geometry
{
    public static function make(float $x, float $y, ?float $z = null, ?float $m = null, ?int $srid = null): self
    {
        return new self(Dimension::fromCoordinates($x, $y, $z, $m), $x, $y, $z, $m, $srid);
    }

    public static function makeGeodetic(float $latitude, float $longitude, ?float $altitude = null): self
    {
        $dimension = $altitude ? Dimension::DIMENSION_3DZ : Dimension::DIMENSION_2D;

        return new self($dimension, $longitude, $latitude, $altitude, null, 4326);
    }

    protected function __construct(
        protected Dimension $dimension,
        protected float     $x,
        protected float     $y,
        protected ?float    $z = null,
        protected ?float    $m = null,
        protected ?int      $srid = null
    ) {
    }

    /**
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    /**
     * @return int|null
     */
    public function getSrid(): ?int
    {
        return $this->srid;
    }

    public function is3d(): bool
    {
        return $this->dimension->has3Dimensions();
    }

    /**
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * @param float $x
     */
    public function setX(float $x): void
    {
        $this->x = $x;
    }

    /**
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * @param float $y
     */
    public function setY(float $y): void
    {
        $this->y = $y;
    }

    /**
     * @return float|null
     */
    public function getZ(): ?float
    {
        return $this->z;
    }

    /**
     * @param float|null $z
     */
    public function setZ(?float $z): void
    {
        $this->z = $z;
        $this->updateDimension();
    }

    /**
     * @return float|null
     */
    public function getM(): ?float
    {
        return $this->m;
    }

    /**
     * @param float|null $m
     */
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
    // * GEODESIC METHODS                                                               *
    // *                                                                                *
    // * Since most Points might use WGS84 as their coordinate system, we provide       *
    // * some additional WGS named functions                                            *
    // **********************************************************************************

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        $this->assertPointIsGeodesic();

        return $this->y;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->assertPointIsGeodesic();
        $this->y = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        $this->assertPointIsGeodesic();

        return $this->x;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->assertPointIsGeodesic();
        $this->x = $longitude;
    }

    /**
     * @return float|null
     */
    public function getAltitude(): ?float
    {
        $this->assertPointIsGeodesic();

        return $this->z;
    }

    /**
     * @param float $altitude
     */
    public function setAltitude(float $altitude): void
    {
        $this->assertPointIsGeodesic();
        $this->z = $altitude;
    }

    private function assertPointIsGeodesic()
    {
        if ($this->srid !== 4326 && $this->srid !== 0) {
            throw new \Exception('Point is not geodesic');
        }
    }
}
