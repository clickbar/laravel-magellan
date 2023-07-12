<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Exception\SridMissmatchException;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

abstract class BaseCast implements CastsAttributes
{
    protected WKBParser $wkbParser;

    protected WKTGenerator $wktGenerator;

    protected int $srid;

    public function __construct(int|null $srid = null)
    {
        $this->wkbParser = App::make(WKBParser::class);
        $this->wktGenerator = App::make(WKTGenerator::class);
        $this->srid = $srid ?? config('magellan.eloquent.default_srid', 4326);
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Clickbar\Magellan\Data\Geometries\Geometry|null
     */
    public function get($model, string $key, mixed $value, array $attributes)
    {
        // The geometry might be already casted on the lowest level if the alias hits an postgis field name
        if ($value instanceof Geometry) {
            return $value;
        }

        return isset($value) ? $this->wkbParser->parse($value) : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        if ($value instanceof Geometry) {
            $geometrySql = $this->geometryToSqlQuery($value);

            if ($value->hasSrid() && $value->getSrid() != $this->srid) {
                if (config('magellan.eloquent.transform_to_database_projection', false)) {
                    $geometrySql = 'ST_TRANSFORM('.$geometrySql.', '.$this->srid.')';
                } else {
                    throw new SridMissmatchException($this->srid, $value->getSrid());
                }
            }

            return DB::raw($geometrySql);
        }

        return $value;
    }

    abstract public function geometryToSqlQuery(Geometry $geometry): string;
}
