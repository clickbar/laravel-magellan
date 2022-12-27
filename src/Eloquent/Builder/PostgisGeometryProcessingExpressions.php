<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Enums\DelaunayTrianglesOutput;
use Clickbar\Magellan\Enums\EndCap;
use Clickbar\Magellan\Enums\Join;
use Clickbar\Magellan\Enums\Side;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;
use RuntimeException;

class PostgisGeometryProcessingExpressions
{
    public static function getBufferExpression($builder, string $bindingType, $geo, ?string $as, float $radius, ?int $numSegQuarterCircle = null, ?int $styleQuadSegs = null, ?EndCap $styleEndCap = null, ?Join $styleJoin = null, ?float $styleMitreLevel = null, ?Side $styleSide = null): Expression
    {
        $arguments = [
            $geo,
            new BindingExpression($radius),
        ];

        $styleParts = [
            "quad_segs=$styleQuadSegs",
            "endcap=$styleEndCap?->value",
            "join=$styleJoin?->value",
            "mitre_level=$styleMitreLevel",
            "side=$styleSide?->value",
        ];

        $styleParameter = collect($styleParts)
            ->filter(fn($part) => !Str::endsWith($part, 'null'))
            ->join(',');

        if (!empty($styleParameter) && $numSegQuarterCircle != null) {
            // TODO: Add propper exception class
            throw new RuntimeException('Cannot use style and numSegQuarterCircle at the same time');
        }

        if (!empty($styleParameter)) {
            $arguments[] = new BindingExpression($styleParameter);
        }

        if ($numSegQuarterCircle != null) {
            $arguments[] = new BindingExpression($numSegQuarterCircle);
        }

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, null, 'ST_Buffer', $as, ...$arguments);
    }

    public static function getBuildAreaExpression($builder, string $bindingType, $geo, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_BuildArea', $as, $geo);
    }

    public static function getCentroidExpression($builder, string $bindingType, $geo, ?bool $useSpheroid = null, ?string $as = 'centroid', ?string $geometryType = null): Expression
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = 'geography';
        }
        $useSpheroid = $useSpheroid ?? true;

        if ($geometryType === 'geography') {
            return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geography', 'ST_Centroid', $as, $geo, new BindingExpression($useSpheroid));
        } else {
            return BuilderUtils::buildPostgisFunction($builder, $bindingType, $geometryType, 'ST_Centroid', $as, $geo);
        }
    }

    public static function getChaikinSmoothingExpression($builder, string $bindingType, $geo, ?int $iterations, ?bool $preserveEndPoints, ?string $as): Expression
    {
        $params = [
            $geo,
        ];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $iterations, $preserveEndPoints);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_ChaikinSmoothing', $as, ...$params);
    }

    public static function getConvaeHullExpression($builder, string $bindingType, $geo, float $pctconvex, ?bool $allowHoles, ?string $as): Expression
    {
        $params = [
            $geo,
            new BindingExpression($pctconvex),
        ];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $allowHoles);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_ConcaveHull', $as, ...$params);
    }

    public static function getConvexHullExpression($builder, string $bindingType, $geometry, ?string $as): Expression
    {
        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_ConvexHull', $as, $geometry);
    }

    public static function getDelaunayTrianglesExpression($builder, string $bindingType, $geo, ?float $tolerance, ?DelaunayTrianglesOutput $output, ?string $as): Expression
    {
        $params = [$geo];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $tolerance, $output?->value);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_DelaunayTriangles', $as, ...$params);
    }


    public static function getFilterByMExpression($builder, string $bindingType, $geo, float $min, ?float $max, ?bool $returnM, ?string $as): Expression
    {
        $params = [
            $geo,
            new BindingExpression($min)
        ];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $max, $returnM);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_FilterByM', $as, ...$params);
    }


    public static function getGeneratePointsExpression($builder, string $bindingType, $geo, int $numberOfPoints, ?int $seed, ?string $as): Expression
    {
        $params = [
            $geo,
            new BindingExpression($numberOfPoints)
        ];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $seed);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_GeneratePoints', $as, ...$params);
    }


    public static function getGeometricMedianExpression($builder, string $bindingType, $geo, ?float $tolerance, ?int $maxIterations, ?bool $failIfNotConverged, ?string $as): Expression
    {
        $params = [$geo];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $tolerance, $maxIterations, $failIfNotConverged);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_GeometricMedian', $as, ...$params);
    }

    public static function getLineMergeExpression($builder, string $bindingType, $geo, ?bool $directed, ?string $as): Expression
    {
        $params = [$geo];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $directed);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_LineMerge', $as, ...$params);
    }

    public static function getMinimumBoundingCircleExpression($builder, string $bindingType, $geo, ?int $numberOfSegmentsPerQuarterCircle, ?string $as): Expression
    {
        $params = [$geo];
        BuilderUtils::appendAsBindingExpressionIfNotNull($params, $numberOfSegmentsPerQuarterCircle);

        return BuilderUtils::buildPostgisFunction($builder, $bindingType, 'geometry', 'ST_MinimumBoundingCircle', $as, ...$params);
    }

}
