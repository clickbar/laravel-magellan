<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Geometries\Geometry;

class GeographyCast extends BaseCast
{
    public function __construct(int|null $srid = null)
    {
        parent::__construct($srid);
    }

    public function geometryToSqlQuery(Geometry $geometry): string
    {
        return $this->wktGenerator->toPostgisGeographySql($geometry, config('magellan.schema', 'public'));
    }
}
