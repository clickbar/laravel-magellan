<?php

namespace Clickbar\Magellan\Database\Builder;

use Clickbar\Magellan\Data\Boxes\Box;
use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\IO\Generator\BaseGenerator;
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
        $generatorClass = config('magellan.sql_generator', WKTGenerator::class);
        $generator = new $generatorClass();
        $paramString = self::transformAndJoinParams($params, $generator, $geometryTypeCastAppend, $builder);

        $expressionString = "$function($paramString)";

        if ($as) {
            $expressionString .= " AS $as";
        }

        return new Expression($expressionString);
    }

    protected static function prepareParams(array $params, Builder|EloquentBuilder $builder, $invadedBuilder, $bindingType, string $geometryTypeCastAppend): array
    {
        foreach ($params as $i => $param) {
            if ($invadedBuilder->isQueryable($param)) {
                [$sub, $bindings] = $invadedBuilder->createSub($param);

                array_splice($params, $i, 1, [new Expression("($sub)")]);
                $invadedBuilder->addBinding($bindings, $bindingType);
            }
            if ($param instanceof BindingExpression) {
                $invadedBuilder->addBinding([$param->getValue()], $bindingType);
            }
            if ($param instanceof GeoParam) {
                $value = $param->getValue();

                if ($value instanceof MagellanBaseExpression) {
                    $invoked = $builder->grammar->wrap($value->invoke($builder, $bindingType, null));
                    array_splice($params, $i, 1, [GeoParam::wrap(new Expression("{$invoked}$geometryTypeCastAppend"))]);
                } elseif ($invadedBuilder->isQueryable($value)) {
                    [$sub, $bindings] = $invadedBuilder->createSub($value);
                    array_splice($params, $i, 1, [GeoParam::wrap(new Expression("($sub)$geometryTypeCastAppend"))]);
                    $invadedBuilder->addBinding($bindings, $bindingType);
                } elseif (is_array($value)) {
                    $wrapped = array_map(fn ($geometry) => GeoParam::wrap($geometry), $value);
                    array_splice($params, $i, 1, [self::prepareParams($wrapped, $builder, $invadedBuilder, $bindingType, $geometryTypeCastAppend)]);
                }
            }
            // TODO: Check if this can be removed, cause all nested MagellanExpressions will be wraped in GeoParam
            if ($param instanceof MagellanBaseExpression) {
                $invoked = $builder->grammar->wrap($param->invoke($builder, $bindingType, null));
                array_splice($params, $i, 1, [new Expression("{$invoked}$geometryTypeCastAppend")]);
            }
            if (is_array($param)) {
                array_splice($params, $i, 1, [self::prepareParams($param, $builder, $invadedBuilder, $bindingType, $geometryTypeCastAppend)]);
            }
        }

        return $params;
    }

    protected static function transformAndJoinParams(array $params, BaseGenerator $generator, string $geometryTypeCastAppend, Builder|EloquentBuilder $builder): string
    {
        $params = array_map(function ($param) use ($geometryTypeCastAppend, $generator, $builder) {
            if (is_array($param)) {
                return 'ARRAY['.self::transformAndJoinParams($param, $generator, $geometryTypeCastAppend, $builder).']';
            }

            if ($param instanceof GeoParam) {
                $value = $param->getValue();

                if ($value instanceof Geometry) {
                    return $generator->toPostgisGeometrySql($value, Config::get('magellan.schema')).$geometryTypeCastAppend;
                }
                if (is_string($value)) {
                    return $builder->grammar->wrap($value).$geometryTypeCastAppend;
                }
                if (is_array($value)) {
                    return 'ARRAY['.self::transformAndJoinParams($value, $generator, $geometryTypeCastAppend, $builder).']';
                }

                // process with default handling of the unwrapped GeoParam
                $param = $value;
            }

            // TODO: Check if this can be removed
            if ($param instanceof Geometry) {
                throw new \Exception('This should not happen, because it is wrapped in GeoParam');
            }

            if ($param instanceof Box2D) {
                return "'{$param->toString()}'::box2d";
            }

            if ($param instanceof Box3D) {
                return "'{$param->toString()}'::box3d";
            }

            if ($param instanceof BindingExpression) {
                return '?';
            }

            return $builder->grammar->wrap($param);
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

    /**
     * Evaluates the given value to a proper value that can be used by the default laravel query parameters
     */
    public static function evaluate($builder, $value, string $bindingType): mixed
    {
        if ($value instanceof MagellanBaseExpression) {
            return $value->invoke($builder, $bindingType);
        }

        if ($value instanceof Geometry) {
            $generatorClass = config('magellan.sql_generator', WKTGenerator::class);
            $generator = new $generatorClass();

            return $generator->generate($value);
        }

        if ($value instanceof Box) {
            return $value->toExpression();
        }

        return $value;
    }
}
