<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Cast\BBoxCast;
use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class BuilderMacros
{
    public function mSelect()
    {
        /** @var EloquentBuilder|\Illuminate\Database\Query\Builder $this */

        return function (MagellanExpression $magellanExpression, string $as = null) {
            $asOrDefault = $as ?? $magellanExpression->getDefaultAs();

            if ($magellanExpression->returnsBbox() && $this instanceof EloquentBuilder) {
                $this->withCasts([$asOrDefault => BBoxCast::class]);
            }

            return $this->addSelect($magellanExpression->invoke($this, 'select', $asOrDefault));
        };
    }

    public function mOrderBy()
    {
        return function (MagellanExpression $magellanExpression, string $direction = 'ASC') {
            return $this->addSelect($magellanExpression->invoke($this, 'order'));
        };
    }

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
