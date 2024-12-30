<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Database\Builder\BindingExpression;
use Clickbar\Magellan\Enums\GeometryType;
use Clickbar\Magellan\IO\Generator\BaseGenerator;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Grammar;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

abstract class MagellanBaseExpression implements Expression
{
    public function __construct(
        public readonly string $postgisFunction,
        public readonly array $params,
        public readonly ?GeometryType $geometryType = GeometryType::Geometry,
    ) {
    }

    public static function numeric(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanNumericExpression
    {
        return new MagellanNumericExpression($postgisFunction, $params, $geometryType);
    }

    public static function boolean(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanBooleanExpression
    {
        return new MagellanBooleanExpression($postgisFunction, $params, $geometryType);
    }

    public static function set(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanSetExpression
    {
        return new MagellanSetExpression($postgisFunction, $params, $geometryType);
    }

    public static function geometry(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanGeometryExpression
    {
        return new MagellanGeometryExpression($postgisFunction, $params, $geometryType);
    }

    public static function geometryOrBox(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanGeometryOrBboxExpression
    {
        return new MagellanGeometryOrBboxExpression($postgisFunction, $params, $geometryType);
    }

    public static function string(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanStringExpression
    {
        return new MagellanStringExpression($postgisFunction, $params, $geometryType);
    }

    public static function bbox(string $postgisFunction, array $params, ?GeometryType $geometryType = GeometryType::Geometry): MagellanBBoxExpression
    {
        return new MagellanBBoxExpression($postgisFunction, $params, $geometryType);
    }

    public function returnsGeometry(): bool
    {
        return $this instanceof MagellanGeometryExpression;
    }

    public function returnsBbox(): bool
    {
        return $this instanceof MagellanBBoxExpression;
    }

    // ######################## Database Expression Building ########################

    public function getValue(Grammar $grammar): float|int|string
    {
        $params = collect($this->params)
            ->filter(function ($param) {
                return ! ($param === null || ($param instanceof GeoParam && $param->getValue() === null));
            })->map(function ($param) {
                if ($param instanceof GeoParam || $param instanceof Expression || $param instanceof \Closure) {
                    return $param;
                }

                return new BindingExpression($param);
            })->toArray();

        $generatorClass = config('magellan.sql_generator', WKTGenerator::class);
        $generator = new $generatorClass();

        $geometryTypeCastAppend = $this->geometryType ? "::{$this->geometryType->value}" : '';
        $preparedParameters = self::prepareParams($grammar, $generator, $geometryTypeCastAppend, $params);
        $paramString = implode(', ', $preparedParameters);

        return Config::get('magellan.schema').'.'."$this->postgisFunction($paramString)";
    }

    private function prepareParams(Grammar $grammar, BaseGenerator $generator, string $geometryTypeCastAppend, array $params): array
    {
        return collect($params)->map(function ($param) use ($geometryTypeCastAppend, $generator, $grammar) {

            // 1. Check if param is queryable -> it's a subquery
            if ($this->isQueryable($param)) {
                // --> Create sub and replace with param array
                return $this->createSub($param);
            }

            // 2. Basic Binding Value
            if ($param instanceof BindingExpression) {
                // --> escape and replace
                return $grammar->escape($param->getValue());
            }

            // 3. Geo Param
            if ($param instanceof GeoParam) {
                $value = $param->getValue();

                if ($value instanceof MagellanBaseExpression) {
                    return $value->getValue($grammar);
                } elseif ($this->isQueryable($value)) {
                    return $this->createSub($value).$geometryTypeCastAppend;
                } elseif (is_array($value)) {
                    $wrapped = array_map(fn ($geometry) => GeoParam::wrap($geometry), $value);
                    $prepared = $this->prepareParams($grammar, $generator, $geometryTypeCastAppend, $wrapped);
                    $imploded = implode(', ', $prepared);

                    return "ARRAY[$imploded]";

                } elseif ($value instanceof Geometry) {
                    return $generator->toPostgisGeometrySql($value, Config::get('magellan.schema'));
                } elseif ($value instanceof Box2D) {
                    return "'{$value->toString()}'::box2d";
                } elseif ($value instanceof Box3D) {
                    return "'{$value->toString()}'::box3d";
                } elseif (is_string($value)) {
                    return $grammar->wrap($value).$geometryTypeCastAppend;
                }
            }

            // 4. array
            if (is_array($param)) {
                $prepared = $this->prepareParams($grammar, $generator, $geometryTypeCastAppend, $param);
                $imploded = implode(', ', $prepared);

                return "ARRAY[$imploded]";
            }

            // 5. expression
            if ($param instanceof Expression) {
                return $param->getValue($grammar);
            }

            // 6. string
            return $grammar->wrap($param);
        })->toArray();
    }

    private function createSub($query): string
    {
        if ($query instanceof Closure) {
            $callback = $query;
            $callback($query = DB::query());
        }

        return "({$query->toRawSql()})";
    }

    private function isQueryable($value): bool
    {
        return $value instanceof self ||
            $value instanceof EloquentBuilder ||
            $value instanceof Relation ||
            $value instanceof Closure;
    }
}
