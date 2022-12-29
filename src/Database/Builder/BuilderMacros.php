<?php

namespace Clickbar\Magellan\Database\Builder;

use Clickbar\Magellan\Cast\BBoxCast;
use Clickbar\Magellan\Cast\GeometryWKBCast;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBBoxExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryOrBboxExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanSetExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression;
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

        /**
         * @param  MagellanBaseExpression  $magellanExpression
         * @param  string|null  $as
         * @return static
         */
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
        /**
         * @param  MagellanNumericExpression|MagellanBooleanExpression  $magellanExpression
         * @param  string|null  $direction
         * @return static
         */
        return function (MagellanNumericExpression|MagellanBooleanExpression $magellanExpression, string $direction = 'ASC') {
            return $this->orderBy($magellanExpression->invoke($this, 'order'), $direction);
        };
    }

    public function mWhere()
    {
        /**
         * @param  MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression  $magellanExpression
         * @param  mixed  $operator
         * @param  mixed  $value
         * @param  string|null  $boolean
         * @return static
         */
        return function (MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression $magellanExpression, $operator = null, $value = null, ?string $boolean = 'and') {
            return $this->where($magellanExpression->invoke($this, 'where'), $operator, $value, $boolean);
        };
    }

    public function mOrWhere()
    {
        /**
         * @param  MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression  $magellanExpression
         * @param  mixed  $operator
         * @param  mixed  $value
         * @return static
         */
        return function (MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression $magellanExpression, $operator = null, $value = null) {
            return $this->orWhere($magellanExpression->invoke($this, 'where'), $operator, $value);
        };
    }

    public function mGroupBy()
    {
        /**
         * @param  array|string|MagellanBaseExpression  ...$groups
         * @return static
         */
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
        /**
         * @param  MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression  $magellanExpression
         * @param  mixed  $operator
         * @param  mixed  $value
         * @param  string|null  $boolean
         * @return static
         */
        return function (MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression $magellanExpression, $operator = null, $value = null, $boolean = 'and') {
            return $this->having($magellanExpression->invoke($this, 'having'), $operator, $value, $boolean);
        };
    }

    public function mFrom()
    {
        /**
         * @param  MagellanSetExpression  $magellanExpression
         * @return static
         */
        return function (MagellanSetExpression $magellanExpression) {
            return $this->from($magellanExpression->invoke($this, 'from'));
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
