<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Illuminate\Database\Query\Expression;

class PostgisGeometryAccessorExpressions
{
    public static function getBoundaryExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Boundary', $as, $geometry);
    }

    public static function getBoundingDiagonalExpression($builder, string $bindingType, $geometry, ?bool $fits, ?string $as): Expression
    {
        $params = [$geometry];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $fits);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_BoundingDiagonal', $as, ...$params);
    }

    public static function getCoordDimExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_CoordDim', $as, $geometry);
    }

    public static function getDimensionExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Dimension', $as, $geometry);
    }

    public static function getEndPointExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_EndPoint', $as, $geometry);
    }

    public static function getEnvelopeExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Envelope', $as, $geometry);
    }

    public static function getExteriorRingExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_ExteriorRing', $as, $geometry);
    }

    public static function getGeometryNExpression($builder, string $bindingType, $geometry, int $n, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_GeometryN', $as, $geometry, new BindingExpression($n));
    }

    public static function getHasArcExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_HasArc', $as, $geometry);
    }

    public static function getInteriorRingNExpression($builder, string $bindingType, $geometry, int $n, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_InteriorRingN', $as, $geometry, new BindingExpression($n));
    }

    public static function getIsClosedExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsClosed', $as, $geometry);
    }

    public static function getIsCollectionExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsCollection', $as, $geometry);
    }

    public static function getIsEmptyExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsEmpty', $as, $geometry);
    }

    public static function getIsPolygonCCWExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsPolygonCCW', $as, $geometry);
    }

    public static function getIsPolygonCWExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsPolygonCW', $as, $geometry);
    }

    public static function getIsRingExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsRing', $as, $geometry);
    }

    public static function getIsSimpleExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_IsSimple', $as, $geometry);
    }

    public static function getMExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_M', $as, $geometry);
    }

    public static function getMemSizeExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_MemSize', $as, $geometry);
    }

    public static function getNDimsExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_NDims', $as, $geometry);
    }

    public static function getNPointsExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_NPoints', $as, $geometry);
    }

    public static function getNRingsExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_NRings', $as, $geometry);
    }

    public static function getNumGeometriesExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_NumGeometries', $as, $geometry);
    }

    public static function getNumInteriorRingsExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_NumInteriorRings', $as, $geometry);
    }

    public static function getNumPatchesExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_NumPatches', $as, $geometry);
    }

    public static function getPatchNExpression($builder, string $bindingType, $geometry, int $n, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_PatchN', $as, $geometry, new BindingExpression($n));
    }

    public static function getPointNExpression($builder, string $bindingType, $geometry, int $n, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_PointN', $as, $geometry, new BindingExpression($n));
    }

    public static function getPointsExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Points', $as, $geometry);
    }

    public static function getStartPointExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_StartPoint', $as, $geometry);
    }

    public static function getSummaryExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, null, 'ST_Summary', $as, $geometry);
    }

    public static function getXExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_X', $as, $geometry);
    }

    public static function getYExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Y', $as, $geometry);
    }

    public static function getZExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Z', $as, $geometry);
    }

    public static function getZmflagExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_Zmflag', $as, $geometry);
    }
}
