<?php

/**
 * Disabled for now, to mitigate issues with the Laravel IDE Helper Generator.
 * PLEASE keep parameter annotations in here with their fully qualified class names, so that the IDE Helper Generator
 * can pick them up properly.
 * See https://github.com/barryvdh/laravel-ide-helper/pull/953
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */

namespace Clickbar\Magellan\Database\Builder;

use Clickbar\Magellan\Cast\BBoxCast;
use Clickbar\Magellan\Cast\GeometryWKBCast;
use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Clickbar\Magellan\Data\Geometries\Geometry;
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
use Illuminate\Database\Query\Builder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class BuilderMacros
{
    public function stSelect()
    {
        /** @var EloquentBuilder|\Illuminate\Database\Query\Builder $this */

        /**
         * @param  \Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression  $magellanExpression
         * @param  string|null  $as
         * @return static
         */
        return function (MagellanBaseExpression $magellanExpression, ?string $as = null) {
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

    public function stOrderBy()
    {
        /**
         * @param  \Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression  $magellanExpression
         * @param  string  $direction
         * @return static
         */
        return function (MagellanNumericExpression|MagellanBooleanExpression $magellanExpression, string $direction = 'ASC') {
            return $this->orderBy($magellanExpression->invoke($this, 'order'), $direction);
        };
    }

    public function stWhere()
    {
        /**
         * @param  \Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBBoxExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryOrBboxExpression|\Clickbar\Magellan\Data\Geometries\Geometry|\Clickbar\Magellan\Data\Boxes\Box2D|\Clickbar\Magellan\Data\Boxes\Box3D|string  $column
         * @param  mixed  $operator
         * @param  mixed  $value
         * @param  string|null  $boolean
         * @return static
         */
        return function (MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression|Geometry|Box2D|Box3D|string $column, $operator = null, $value = null, ?string $boolean = 'and') {
            return $this->where(
                BuilderUtils::evaluate($this, $column, 'where'),
                BuilderUtils::evaluate($this, $operator, 'where'),
                BuilderUtils::evaluate($this, $value, 'where'),
                $boolean);
        };
    }

    public function stOrWhere()
    {
        /**
         * @param  \Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBBoxExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryOrBboxExpression|\Clickbar\Magellan\Data\Geometries\Geometry|\Clickbar\Magellan\Data\Boxes\Box2D|\Clickbar\Magellan\Data\Boxes\Box3D|string  $column
         * @param  mixed  $operator
         * @param  mixed  $value
         * @return static
         */
        return function (MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression|Geometry|Box2D|Box3D|string $column, $operator = null, $value = null) {
            return $this->orWhere(
                BuilderUtils::evaluate($this, $column, 'where'),
                BuilderUtils::evaluate($this, $operator, 'where'),
                BuilderUtils::evaluate($this, $value, 'where'),
            );
        };
    }

    public function stGroupBy()
    {
        /**
         * @param  array|string|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression  ...$groups
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

    public function stHaving()
    {
        /**
         * @param  \Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanBBoxExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression|\Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryOrBboxExpression|string  $magellanExpression
         * @param  mixed  $operator
         * @param  mixed  $value
         * @param  string|null  $boolean
         * @return static
         */
        return function (MagellanStringExpression|MagellanBooleanExpression|MagellanBBoxExpression|MagellanNumericExpression|MagellanGeometryExpression|MagellanGeometryOrBboxExpression|string $magellanExpression, $operator = null, $value = null, $boolean = 'and') {
            return $this->having(
                BuilderUtils::evaluate($this, $magellanExpression, 'having'),
                BuilderUtils::evaluate($this, $operator, 'having'),
                BuilderUtils::evaluate($this, $value, 'having'),
                $boolean);
        };
    }

    public function stFrom()
    {
        /**
         * @param  \Clickbar\Magellan\Database\MagellanExpressions\MagellanSetExpression  $magellanExpression
         * @return static
         */
        return function (MagellanSetExpression $magellanExpression, ?string $as = null) {
            // NOTE: the `as` field has to be included in the DB expression instead of using the `from` method with the
            // `as` parameter, because the latter will try to use the expression in a string concatenation with `as`.
            /** @var Builder $this */
            $this->from = $magellanExpression->invoke($this, 'from', $as);

            return $this;
        };
    }

    public function toGeojsonFeatureCollection(): Closure
    {
        return function (): string {
            // Create a fresh query, on the same connection, grammar and processor as the original query
            // @phpstan-ignore-next-line
            $freshQuery = ($this instanceof EloquentBuilder) ? $this->toBase()->newQuery() : $this->newQuery();

            return $freshQuery
                ->selectRaw("json_build_object('type', 'FeatureCollection', 'features', COALESCE(json_agg(ST_AsGeoJSON(f.*)::json), ('[]')::json)) AS geojson")
                ->fromSub($this, 'f')
                ->first()
                ->geojson;
        };
    }
}
