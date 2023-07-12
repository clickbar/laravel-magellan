<?php

namespace Clickbar\Magellan\Commands\Utils;

use Clickbar\Magellan\Cast\GeographyCast;
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
        if ($this->geometry_type === 'geometry') {
            return GeometryCast::class;
        }

        return GeographyCast::class;
    }

    public function toCastValue(): string
    {
        if ($this->geometry_type === 'geometry') {
            return GeometryCast::class.":$this->srid";
        }

        return GeographyCast::class.":$this->srid";

    }

    public function toCastLineCode(): string
    {
        if ($this->geometry_type === 'geometry') {
            return "'$this->column' => GeometryCast::class.':$this->srid',";
        }

        return "'$this->column' => GeographyCast::class.':$this->srid',";
    }
}
