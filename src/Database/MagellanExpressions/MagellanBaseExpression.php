<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

use Clickbar\Magellan\Database\Builder\StringifiesQueryParameters;
use Clickbar\Magellan\Database\Builder\ValueParameter;
use Clickbar\Magellan\IO\Generator\BaseGenerator;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Illuminate\Support\Facades\Config;

abstract class MagellanBaseExpression implements Expression
{
    use StringifiesQueryParameters;

    public function __construct(
        public readonly string $postgisFunction,
        public readonly array $params,
    ) {
    }

    public static function numeric(string $postgisFunction, array $params): MagellanNumericExpression
    {
        return new MagellanNumericExpression($postgisFunction, $params);
    }

    public static function boolean(string $postgisFunction, array $params): MagellanBooleanExpression
    {
        return new MagellanBooleanExpression($postgisFunction, $params);
    }

    public static function set(string $postgisFunction, array $params): MagellanSetExpression
    {
        return new MagellanSetExpression($postgisFunction, $params);
    }

    public static function geometry(string $postgisFunction, array $params): MagellanGeometryExpression
    {
        return new MagellanGeometryExpression($postgisFunction, $params);
    }

    public static function geometryOrBox(string $postgisFunction, array $params): MagellanGeometryOrBboxExpression
    {
        return new MagellanGeometryOrBboxExpression($postgisFunction, $params);
    }

    public static function string(string $postgisFunction, array $params): MagellanStringExpression
    {
        return new MagellanStringExpression($postgisFunction, $params);
    }

    public static function bbox(string $postgisFunction, array $params): MagellanBBoxExpression
    {
        return new MagellanBBoxExpression($postgisFunction, $params);
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
            ->filter(fn ($param) => $param !== null)
            ->map(function ($param) {

                if ($param instanceof Expression || $param instanceof \Closure || $param instanceof ColumnParameter || is_array($param)) {
                    return $param;
                }

                return new ValueParameter($param);
            })->toArray();

        $generatorClass = config('magellan.sql_generator', WKTGenerator::class);
        $generator = new $generatorClass();

        $preparedParameters = self::prepareParams($grammar, $generator, $params);
        $paramString = implode(', ', $preparedParameters);

        return Config::get('magellan.schema').'.'."$this->postgisFunction($paramString)";
    }

    private function prepareParams(Grammar $grammar, BaseGenerator $generator, array $params): array
    {
        return collect($params)->map(function ($param) use ($generator, $grammar) {

            if (is_array($param)) {
                $prepared = $this->prepareParams($grammar, $generator, $param);
                $imploded = implode(', ', $prepared);

                return "ARRAY[$imploded]";
            }

            return $this->stringifyQueryParameter($grammar, $param);
        })->toArray();
    }
}
