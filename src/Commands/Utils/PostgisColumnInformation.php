<?php

namespace Clickbar\Magellan\Commands\Utils;

use Clickbar\Magellan\Cast\GeometryCast;

class PostgisColumnInformation
{
    public function __construct(
        protected string $geometry_type,
        protected string $type,
        protected int $srid,
        protected string $column,
        protected int $coord_dimension,
    ) {
    }

    public function getGeometryType(): string
    {
        return $this->geometry_type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSrid(): int
    {
        return $this->srid;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getCoordDimension(): int
    {
        return $this->coord_dimension;
    }

    public function getCasterClass(): string
    {
        return GeometryCast::class;
    }

    public function toCastValue(): string
    {
        return GeometryCast::class;
    }

    public function toCastLineCode(): string
    {
        return "'$this->column' => GeometryCast::class,";
    }
}
