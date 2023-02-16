<?php

namespace Clickbar\Magellan\Commands\Utils;

class PostgisColumnInformation
{
    public function __construct(
        protected string $geometry_type,
        protected string $type,
        protected int $srid,
        protected string $column,
        protected int $coord_dimesion,
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

    public function getCoordDimesion(): int
    {
        return $this->coord_dimesion;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->geometry_type,
            'srid' => $this->srid,
        ];
    }
}
