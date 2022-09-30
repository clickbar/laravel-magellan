<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Closure;
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
            return DB::query()
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
            // @phpstan-ignore-next-line - buildPostgisFunction is available but defined in another mixin
            return $this->whereRaw($this->buildPostgisFunction('where', 'geometry', 'ST_CONTAINS', $haystack, $needle));
        };
    }

    public function selectCentroid(): Closure
    {
        return function ($geometry, ?bool $useSpheroid = null) {
            if ($useSpheroid !== null) {
                // @phpstan-ignore-next-line - buildPostgisFunction is available but defined in another mixin
                return $this->addSelect($this->buildPostgisFunction('select', 'geography', 'ST_Centroid', $geometry, DB::raw($useSpheroid)));
            }

            // @phpstan-ignore-next-line - buildPostgisFunction is available but defined in another mixin
            return $this->addSelect($this->buildPostgisFunction('select', null, 'ST_Centroid', $geometry));
        };
    }
}
