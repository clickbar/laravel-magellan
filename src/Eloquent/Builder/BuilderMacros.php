<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * @mixin Builder
 */
class BuilderMacros
{
    public function toGeojsonFeatureCollection(): Closure
    {
        return function (): string {
            return DB::query()
                ->selectRaw("json_build_object('type', 'FeatureCollection', 'features', json_agg(ST_AsGeoJSON(f.*)::json)) AS geojson")
                ->fromSub($this, 'f')
                ->first()
                ->geojson;
        };
    }

    protected function buildPostgisFunction(): Closure|Expression
    {
        return function (string $bindingType, ?string $geometryType, string $function, ...$params): Expression {
            $geometryTypeCastAppend = $geometryType ? "::$geometryType" : '';

            foreach ($params as $i => $param) {
                if ($this->isQueryable($param)) {
                    [$sub, $bindings] = $this->createSub($param);

                    array_splice($params, $i, 1, [new Expression("($sub)$geometryTypeCastAppend")]);

                    return $this->addBinding($bindings, $bindingType)
                        ->buildPostgisFunction($bindingType, $geometryType, $function, ...$params);
                }
            }

            $wktGenerator = new WKTGenerator();
            $params = array_map(function ($param) use ($geometryTypeCastAppend, $wktGenerator) {
                if ($param instanceof Geometry) {
                    return $wktGenerator->toPostgisGeometrySql($param, config('magellan.schema')).$geometryTypeCastAppend;
                }
                if ($param instanceof Expression) {
                    if (is_bool($param->getValue())) {
                        return $param->getValue() ? 'true' : 'false';
                    }

                    return $param;
                }

                return $param.$geometryTypeCastAppend;
            }, $params);

            $paramString = implode(', ', array_map(fn ($param) => (string) $param, $params));

            return new Expression("$function($paramString)");
        };
    }

    public function whereContainsGeometry(): Closure
    {
        return function ($haystack, $needle) {
            // Use whereRaw, because where with only one param results in null check where statenment
            return $this->whereRaw($this->buildPostgisFunction('where', 'geometry', 'ST_CONTAINS', $haystack, $needle));
        };
    }

    public function orderByDistanceSphere(): Closure
    {
        return function ($a, $b, string $direction = 'ASC') {
            return $this->orderBy(
                $this->buildPostgisFunction('order', 'geometry', 'ST_DistanceSphere', $a, $b),
                $direction
            );
        };
    }

    public function selectDistanceSphere(): Closure
    {
        return function ($a, $b) {
            return $this->addSelect($this->buildPostgisFunction('select', 'geometry', 'ST_DistanceSphere', $a, $b));
        };
    }

    public function whereDistanceSphere(): Closure
    {
        return function ($a, $b, $operator = null, $value = null) {
            return $this->where($this->buildPostgisFunction('where', 'geometry', 'ST_DistanceSphere', $a, $b), $operator, $value);
        };
    }

    public function selectCentroid(): Closure
    {
        return function ($geometry, ?bool $useSpheroid = null) {
            if ($useSpheroid !== null) {
                return $this->addSelect($this->buildPostgisFunction('select', 'geography', 'ST_Centroid', $geometry, DB::raw($useSpheroid)));
            }

            return $this->addSelect($this->buildPostgisFunction('select', null, 'ST_Centroid', $geometry));
        };
    }
}
