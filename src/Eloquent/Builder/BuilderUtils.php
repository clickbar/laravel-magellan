<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Boxes\Box2D;
use Clickbar\Magellan\Boxes\Box3D;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Config;

class BuilderUtils
{
    public static function buildPostgisFunction(Builder|EloquentBuilder $builder, string $bindingType, ?string $geometryType, string $function, ?string $as = null, ...$params): Expression
    {
        if ($builder instanceof EloquentBuilder) {
            $builder = $builder->getQuery();
        }

        $invadedBuilder = invade($builder);
        $geometryTypeCastAppend = $geometryType ? "::$geometryType" : '';

        $params = self::prepareParams($params, $builder, $invadedBuilder, $bindingType, $geometryTypeCastAppend);
        $wktGenerator = new WKTGenerator();
        $paramString = self::transformAndJoinParams($params, $wktGenerator, $geometryTypeCastAppend, $builder);

        $expressionString = "$function($paramString)";

        if ($as) {
            $expressionString .= " AS $as";
        }

        return new Expression($expressionString);
    }

    protected static function prepareParams(array $params, $builder, $invadedBuilder, $bindingType, string $geometryTypeCastAppend): array
    {
        foreach ($params as $i => $param) {
            if ($invadedBuilder->isQueryable($param)) {
                [$sub, $bindings] = $invadedBuilder->createSub($param);

                array_splice($params, $i, 1, [new Expression("($sub)$geometryTypeCastAppend")]);
                $invadedBuilder->addBinding($bindings, $bindingType);
            }
            if ($param instanceof BindingExpression) {
                $invadedBuilder->addBinding([$param->getValue()], $bindingType);
            }
            if ($param instanceof MagellanBaseExpression) {
                $invoked = $param->invoke($builder, $bindingType, null);
                array_splice($params, $i, 1, [new Expression("{$invoked}$geometryTypeCastAppend")]);
            }
            if (is_array($param)) {
                array_splice($params, $i, 1, [self::prepareParams($param, $builder, $invadedBuilder, $bindingType, $geometryTypeCastAppend)]);
            }
        }

        return $params;
    }

    protected static function transformAndJoinParams(array $params, WKTGenerator $wktGenerator, string $geometryTypeCastAppend, $builder): string
    {
        $params = array_map(function ($param) use ($geometryTypeCastAppend, $wktGenerator, $builder) {
            if (is_array($param)) {
                return 'ARRAY['.self::transformAndJoinParams($param, $wktGenerator, $geometryTypeCastAppend, $builder).']';
            }

            if ($param instanceof Geometry) {
                return $wktGenerator->toPostgisGeometrySql($param, Config::get('magellan.schema')).$geometryTypeCastAppend;
            }

            if ($param instanceof Box2D) {
                return "'{$param->toString()}'::box2d";
            }

            if ($param instanceof Box3D) {
                return "'{$param->toString()}'::box3d";
            }

            if ($param instanceof Expression) {
                if (is_bool($param->getValue())) {
                    return $param->getValue() ? 'true' : 'false';
                }

                return $param;
            }

            if ($param instanceof BindingExpression) {
                return '?';
            }

            return $builder->grammar->wrap($param).$geometryTypeCastAppend;
        }, $params);

        return implode(', ', array_map(fn ($param) => (string) $param, $params));
    }

    public static function appendAsBindingExpressionIfNotNull(array &$array, ...$values)
    {
        foreach ($values as $value) {
            if ($value != null) {
                $array[] = new BindingExpression($value);
            }
        }
    }
}
