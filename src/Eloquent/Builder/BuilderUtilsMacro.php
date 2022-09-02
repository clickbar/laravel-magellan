<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Illuminate\Database\Query\Expression;

/**
 * @mixin
 */
class BuilderUtilsMacro
{
    public function buildPostgisFunction(): \Closure|Expression
    {
        return function (string $bindingType, ?string $geometryType, string $function, ?string $as = null, ...$params): Expression {
            $geometryTypeCastAppend = $geometryType ? "::$geometryType" : '';

            foreach ($params as $i => $param) {
                if ($this->isQueryable($param)) {
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
                    return $wktGenerator->toPostgisGeometrySql($param, config('magellan.schema')).$geometryTypeCastAppend;
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

                return $param.$geometryTypeCastAppend;
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
