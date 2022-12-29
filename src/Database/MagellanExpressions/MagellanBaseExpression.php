<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

use Clickbar\Magellan\Database\Builder\BindingExpression;
use Clickbar\Magellan\Database\Builder\BuilderUtils;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;

abstract class MagellanBaseExpression
{
    public function __construct(
        protected readonly string $postgisFunction,
        protected readonly array $params,
        protected readonly ?string $geometryType = 'geometry',
    ) {
    }

    public static function numeric(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanNumericExpression
    {
        return new MagellanNumericExpression($postgisFunction, $params, $geometryType);
    }

    public static function boolean(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanBooleanExpression
    {
        return new MagellanBooleanExpression($postgisFunction, $params, $geometryType);
    }

    public static function set(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanSetExpression
    {
        return new MagellanSetExpression($postgisFunction, $params, $geometryType);
    }

    public static function geometry(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanGeometryExpression
    {
        return new MagellanGeometryExpression($postgisFunction, $params, $geometryType);
    }

    public static function geometryOrBox(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanGeometryOrBboxExpression
    {
        return new MagellanGeometryOrBboxExpression($postgisFunction, $params, $geometryType);
    }

    public static function string(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanStringExpression
    {
        return new MagellanStringExpression($postgisFunction, $params, $geometryType);
    }

    public static function bbox(string $postgisFunction, array $params, ?string $geometryType = 'geometry'): MagellanBBoxExpression
    {
        return new MagellanBBoxExpression($postgisFunction, $params, $geometryType);
    }

    public function invoke($builder, string $bindingType, ?string $as = null): Expression
    {
        // Remove null values from params and map to the BindingValue if it is no GeoParam or Expression
        $params = collect($this->params)
            ->filter(function ($param) {
                return ! ($param === null || ($param instanceof GeoParam && $param->getValue() === null));
            })->map(function ($param) {
                if ($param instanceof GeoParam || $param instanceof Expression) {
                    return $param;
                }

                return new BindingExpression($param);
            });

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, $this->geometryType, $this->postgisFunction, $as, ...$params);
    }

    public function returnsGeometry(): bool
    {
        return $this instanceof MagellanGeometryExpression;
    }

    public function returnsBbox(): bool
    {
        return $this instanceof MagellanBBoxExpression;
    }

    public function returnsSet(): bool
    {
        return $this instanceof MagellanSetExpression;
    }

    public function canBeOrdered(): bool
    {
        return $this instanceof MagellanNumericExpression;
    }

    public function canBeGrouped(): bool
    {
        return ! $this instanceof MagellanGeometryExpression;
    }

    public function getDefaultAs(): string
    {
        return Str::of($this->postgisFunction)->remove('ST_')->camel();
    }

    /**
     * @return string
     */
    public function getPostgisFunction(): string
    {
        return $this->postgisFunction;
    }
}
