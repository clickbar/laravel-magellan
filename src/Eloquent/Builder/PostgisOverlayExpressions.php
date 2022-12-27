<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Illuminate\Database\Query\Expression;

class PostgisOverlayExpressions
{
    public static function getDifferenceExpression($builder, string $bindingType, $geometryA, $geometryB, ?float $gridSize, ?string $as): Expression
    {
        $params = [$geometryA, $geometryB];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $gridSize);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Difference', $as, ...$params);
    }
}
