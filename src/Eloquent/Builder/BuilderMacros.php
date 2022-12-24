<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

/**
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Clickbar\Magellan\Eloquent\Builder\BuilderUtilsMacro
 */
class BuilderMacros
{
    public function toGeojsonFeatureCollection(): Closure
    {
        return function (): string {
            // Create a fresh query, on the same connection, grammar and processor as the original query
            // @phpstan-ignore-next-line
            $freshQuery = ($this instanceof EloquentBuilder) ? $this->toBase()->newQuery() : $this->newQuery();

            return $freshQuery
              ->selectRaw("json_build_object('type', 'FeatureCollection', 'features', json_agg(ST_AsGeoJSON(f.*)::json)) AS geojson")
              ->fromSub($this, 'f')
              ->first()
              ->geojson;
        };
    }

    public function whereContainsGeometry(): Closure
    {
        return function ($haystack, $needle) {
            // Use whereRaw, because where with only one param results in null check where statenment
            return $this->whereRaw($this->buildPostgisFunction('where', 'geometry', 'ST_CONTAINS', $haystack, $needle));
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
