<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Boxes\Box2D;
use Clickbar\Magellan\Boxes\Box3D;
use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Config;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class BuilderUtilsMacro
{
    public function buildPostgisFunction(): \Closure|Expression
    {
        return function (string $bindingType, ?string $geometryType, string $function, ?string $as = null, ...$params): Expression {
            $geometryTypeCastAppend = $geometryType ? "::$geometryType" : '';

            foreach ($params as $i => $param) {
                // @phpstan-ignore-next-line - `this` is bound to the query builder because of the mixin
                if ($this->isQueryable($param)) {
                    // @phpstan-ignore-next-line - `this` is bound to the query builder because of the mixin
                    [$sub, $bindings] = $this->createSub($param);

                    array_splice($params, $i, 1, [new Expression("($sub)$geometryTypeCastAppend")]);

                    return $this->addBinding($bindings, $bindingType)
                        ->buildPostgisFunction($bindingType, $geometryType, $function, $as, ...$params);
                }
                if ($param instanceof BindingExpression) {
                    $this->addBinding([$param->getValue()], $bindingType);
                }
            }

            $wktGenerator = new WKTGenerator();
            $params = array_map(function ($param) use ($geometryTypeCastAppend, $wktGenerator) {
                if ($param instanceof Geometry) {
                    return $wktGenerator->toPostgisGeometrySql($param, Config::get('magellan.schema')) . $geometryTypeCastAppend;
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

                return $param . $geometryTypeCastAppend;
            }, $params);

            $paramString = implode(', ', array_map(fn ($param) => (string) $param, $params));
            $expressionString = "$function($paramString)";

            if ($as) {
                $expressionString .= " AS $as";
            }

            return new Expression($expressionString);
        };
    }
}
