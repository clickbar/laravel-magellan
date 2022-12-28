<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Clickbar\Magellan\Eloquent\Builder\BuilderUtils
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
}
