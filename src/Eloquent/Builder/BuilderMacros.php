<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Cast\BBoxCast;
use Clickbar\Magellan\Cast\GeometryWKBCast;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanBooleanExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanNumericExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanSetExpression;
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

        return function (MagellanBaseExpression $magellanExpression, string $as = null) {
            $asOrDefault = mb_strtolower($as ?? $magellanExpression->getDefaultAs());

            if ($this instanceof EloquentBuilder) {
                if ($magellanExpression->returnsBbox()) {
                    $this->withCasts([$asOrDefault => BBoxCast::class]);
                }

                if ($magellanExpression->returnsGeometry()) {
                    $this->withCasts([$asOrDefault => GeometryWKBCast::class]);
                }
            }

            return $this->addSelect($magellanExpression->invoke($this, 'select', $asOrDefault));
        };
    }

    public function mOrderBy()
    {
        return function (MagellanNumericExpression|MagellanBooleanExpression $magellanExpression, string $direction = 'ASC') {
            return $this->orderBy($magellanExpression->invoke($this, 'order'), $direction);
        };
    }

    public function mWhere()
    {
        return function (MagellanBaseExpression $magellanExpression, $operator = null, $value = null, ?string $boolean = 'and') {
            return $this->where($magellanExpression->invoke($this, 'order'), $operator, $value, $boolean);
        };
    }

    public function mOrWhere()
    {
        return function (MagellanBaseExpression $magellanExpression, $operator = null, $value = null) {
            return $this->orWhere($magellanExpression->invoke($this, 'order'), $operator, $value);
        };
    }

    public function mGroupBy()
    {
        return function (...$groups) {
            $invokedGroups = array_map(function ($group) {
                if ($group instanceof MagellanBaseExpression) {
                    return $group->invoke($this, 'groupBy');
                }

                return $group;
            }, $groups);

            return $this->groupBy($invokedGroups);
        };
    }

    public function mHaving()
    {
        return function (MagellanBaseExpression $magellanExpression, $operator = null, $value = null, $boolean = 'and') {
            return $this->having($magellanExpression->invoke($this, 'having'), $operator, $value, $boolean);
        };
    }

    public function mFrom()
    {
        return function (MagellanSetExpression $magellanExpression) {
            if (! $magellanExpression->returnsSet()) {
                throw new \Exception("Cannot use {$magellanExpression->getPostgisFunction()} as from since it does not return a set");
            }

            return $this->from($magellanExpression->invoke($this, 'having'));
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
