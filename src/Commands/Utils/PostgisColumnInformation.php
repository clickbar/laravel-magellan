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

    /**
     * @return string
     */
    public function getGeometryType(): string
    {
        return $this->geometry_type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSrid(): int
    {
        return $this->srid;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getCoordDimesion(): int
    {
        return $this->coord_dimesion;
    }
}
