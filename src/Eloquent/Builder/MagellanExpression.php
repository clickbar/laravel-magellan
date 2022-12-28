<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Enums\ExpressionReturnType;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;

class MagellanExpression
{
    public function __construct(
        protected readonly string $postgisFunction,
        protected readonly ExpressionReturnType $returnType,
        protected readonly array $params,
        protected readonly array $optionalParams = [],
        protected readonly ?string $geometryType = 'geometry',
    ) {
    }

    public static function numeric(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): self
    {
        return new MagellanExpression($postgisFunction, ExpressionReturnType::Numeric, $params, $optionalParams, $geometryType);
    }

    public static function boolean(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): self
    {
        return new MagellanExpression($postgisFunction, ExpressionReturnType::Boolean, $params, $optionalParams, $geometryType);
    }

    public static function geometry(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): self
    {
        return new MagellanExpression($postgisFunction, ExpressionReturnType::Geometry, $params, $optionalParams, $geometryType);
    }

    public static function geometryOrBox(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): self
    {
        return new MagellanExpression($postgisFunction, ExpressionReturnType::GeometryOrBBox, $params, $optionalParams, $geometryType);
    }

    public static function string(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): self
    {
        return new MagellanExpression($postgisFunction, ExpressionReturnType::String, $params, $optionalParams, $geometryType);
    }

    public static function bbox(string $postgisFunction, array $params, array $optionalParams = [], ?string $geometryType = 'geometry'): self
    {
        return new MagellanExpression($postgisFunction, ExpressionReturnType::BBox, $params, $optionalParams, $geometryType);
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
        return $this->returnType === ExpressionReturnType::Geometry;
    }

    public function returnsBbox(): bool
    {
        return $this->returnType === ExpressionReturnType::BBox;
    }

    public function canBeOrdered(): bool
    {
        return $this->returnType === ExpressionReturnType::Numeric;
    }

    public function canBeGrouped(): bool
    {
        return $this->returnType !== ExpressionReturnType::Geometry;
    }

    public function getDefaultAs(): string
    {
        return Str::of($this->postgisFunction)->remove('ST_')->camel();
    }
}
