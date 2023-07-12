<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\GeometryFactory;
use Clickbar\Magellan\Exception\SridMissmatchException;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class GeographyCast extends BaseCast
{


    public function __construct(int|null $srid = null)
    {
        parent::__construct($srid);
    }

    function geometryToSqlQuery(Geometry $geometry): string
    {
        return $this->wktGenerator->toPostgisGeographySql($geometry, config('magellan.schema', 'public'));
    }
}
