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
        protected readonly array $optionalParams = [],
        protected readonly ?string $geometryType = 'geometry',
    ) {
    }

    public static function numeric(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanNumericExpression
    {
        return new MagellanNumericExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public static function boolean(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanBooleanExpression
    {
        return new MagellanBooleanExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public static function set(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanSetExpression
    {
        return new MagellanSetExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public static function geometry(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanGeometryExpression
    {
        return new MagellanGeometryExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public static function geometryOrBox(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanGeometryOrBboxExpression
    {
        return new MagellanGeometryOrBboxExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public static function string(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanStringExpression
    {
        return new MagellanStringExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public static function bbox(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): MagellanBBoxExpression
    {
        return new MagellanBBoxExpression($postgisFunction, $params, $optionalParams, $geometryType);
    }

    public function invoke($builder, string $bindingType, ?string $as = null): Expression
    {
        $params = $this->params;
        foreach ($this->optionalParams as $optionalParam) {
            if ($optionalParam !== null) {
                $params[] = new BindingExpression($optionalParam);
            }
        }

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
