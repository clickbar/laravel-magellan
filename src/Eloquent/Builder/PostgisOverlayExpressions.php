<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Illuminate\Database\Query\Expression;

class PostgisOverlayExpressions
{
    public static function getClipByBox2DExpression($builder, string $bindingType, $geometry, $box, ?string $as): Expression
    {
        $params = [$geometry, $box];

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_ClipByBox2D', $as, ...$params);
    }

    public static function getDifferenceExpression($builder, string $bindingType, $geometryA, $geometryB, ?float $gridSize, ?string $as): Expression
    {
        $params = [$geometryA, $geometryB];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $gridSize);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Difference', $as, ...$params);
    }
}
