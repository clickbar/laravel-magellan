<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class PostgisMeasurementExpressions
{
    public static function getDistanceExpression($builder, string $bindingType, $a, $b, ?bool $useSpheroid = null, ?string $as = 'distance', ?string $geometryType = null)
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = 'geography';
        }
        $useSpheroid = $useSpheroid ?? true;

        if ($geometryType === 'geography') {
            return $builder->buildPostgisFunction($bindingType, 'geography', 'ST_Distance', $as, $a, $b, DB::raw($useSpheroid));
        } else {
            return $builder->buildPostgisFunction($bindingType, $geometryType, 'ST_Distance', $as, $a, $b);
        }
    }

    public static function getDistanceSphereExpression($builder, string $bindingType, $a, $b, ?string $as = 'distance')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_DistanceSphere', $as, $a, $b);
    }

    public static function getDistanceSpheroidExpression($builder, string $bindingType, $a, $b, ?string $measurementSpheroid = 'SPHEROID["WGS 84",6378137,298.257223563]', ?string $as = 'distance_spheroid')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_DistanceSpheroid', $as, $a, $b, new BindingExpression($measurementSpheroid));
    }

    public static function getDistance3dExpression($builder, string $bindingType, $a, $b, ?string $as = 'distance_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DDistance', $as, $a, $b);
    }

    public static function getDistanceFrechetExpression($builder, string $bindingType, $a, $b, ?float $densityFrac, ?string $as = 'distance_frechet')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_FrechetDistance', $as, $a, $b, new BindingExpression($densityFrac));
    }

    public static function getDistanceHausdorffExpression($builder, string $bindingType, $a, $b, ?float $densityFrac, ?string $as = 'distance_hausdorff')
    {
        if ($densityFrac) {
            return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_HausdorffDistance', $as, $a, $b, new BindingExpression($densityFrac));
        } else {
            return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_HausdorffDistance', $as, $a, $b);
        }
    }

    public static function getAreaExpression($builder, string $bindingType, $a, ?bool $useSpheroid = null, ?string $as = 'area', ?string $geometryType = null)
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = 'geography';
        }
        $useSpheroid = $useSpheroid ?? true;

        if ($geometryType === 'geography') {
            return $builder->buildPostgisFunction($bindingType, 'geography', 'ST_Area', $as, $a, DB::raw($useSpheroid));
        } else {
            return $builder->buildPostgisFunction($bindingType, $geometryType, 'ST_Area', $as, $a);
        }
    }

    public static function getLengthExpression($builder, string $bindingType, $lineString2d, ?bool $useSpheroid = null, ?string $as = 'length', ?string $geometryType = null)
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = 'geography';
        }
        $useSpheroid = $useSpheroid ?? true;

        if ($geometryType === 'geography') {
            return $builder->buildPostgisFunction($bindingType, 'geography', 'ST_Length', $as, $lineString2d, DB::raw($useSpheroid));
        } else {
            return $builder->buildPostgisFunction($bindingType, $geometryType, 'ST_Length', $as, $lineString2d);
        }
    }

    public static function getLength3dExpression($builder, string $bindingType, $lineString3d, ?string $as = 'length_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DLength', $as, $lineString3d);
    }

    public static function getLengthSpheroidExpression($builder, string $bindingType, $a, ?string $spheroid = 'SPHEROID["WGS 84",6378137,298.257223563]', ?string $as = 'length_spheroid')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_LengthSpheroid', $as, $a, new BindingExpression($spheroid));
    }

    public static function getMaxDistanceExpression($builder, string $bindingType, $a, $b, ?string $as = 'max_distance')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_MaxDistance', $as, $a, $b);
    }

    public static function getMaxDistance3dExpression($builder, string $bindingType, $a, $b, ?string $as = 'max_distance_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DMaxDistance', $as, $a, $b);
    }

    public static function getClosestPointExpression($builder, string $bindingType, $a, $b, ?string $as = 'closest_point')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_ClosestPoint', $as, $a, $b);
    }

    public static function getClosestPoint3dExpression($builder, string $bindingType, $a, $b, ?string $as = 'closest_point_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DClosestPoint', $as, $a, $b);
    }

    public static function getLongestLineExpression($builder, string $bindingType, $a, $b, ?string $as = 'longest_line')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_LongestLine', $as, $a, $b);
    }

    public static function getLongestLineExpression3d($builder, string $bindingType, $a, $b, ?string $as = 'longest_line_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DLongestLine', $as, $a, $b);
    }

    public static function getShortestLineExpression($builder, string $bindingType, $a, $b, ?string $as = 'shortest_line')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_ShortestLine', $as, $a, $b);
    }

    public static function getShortestLineExpression3d($builder, string $bindingType, $a, $b, ?string $as = 'shortest_line_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DShortestLine', $as, $a, $b);
    }

    public static function getPerimeterExpression($builder, string $bindingType, $geometry, ?bool $useSpheroid = null, ?string $as = 'length', ?string $geometryType = null)
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = 'geography';
        }
        $useSpheroid = $useSpheroid ?? true;

        if ($geometryType === 'geography') {
            return $builder->buildPostgisFunction($bindingType, 'geography', 'ST_Perimeter', $as, $geometry, DB::raw($useSpheroid));
        } else {
            return $builder->buildPostgisFunction($bindingType, $geometryType, 'ST_Perimeter', $as, $geometry);
        }
    }

    public static function getPerimeter3dExpression($builder, string $bindingType, $geometry, ?string $as = 'perimeter_3d')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DPerimeter', $as, $geometry);
    }

    public static function getAzimuthExpression($builder, string $bindingType, $a, $b, ?string $as = 'azimuth', ?string $type = null)
    {
        return $builder->buildPostgisFunction($bindingType, $type, 'ST_DistanceSphere', $as, $a, $b);
    }

    public static function getAngleFromPointsExpression($builder, string $bindingType, $p1, $p2, $p3, $p4, ?string $as = 'angle')
    {
        if ($p4) {
            return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_Angle', $as, $p1, $p2, $p3, $p4);
        }

        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_Angle', $as, $p1, $p2, $p3);
    }

    public static function getAngleFromLinesExpression($builder, string $bindingType, $lineString1, $lineString2, ?string $as = 'angle')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_Angle', $as, $lineString1, $lineString2);
    }

    public static function getProjectExpression($builder, string $bindingType, $geography, float $distance, float|Expression $azimuth, ?string $as = 'project')
    {
        return $builder->buildPostgisFunction($bindingType, 'geography', 'ST_Project', $as, $geography, new BindingExpression($distance), $azimuth);
    }

    public static function getMinimumClearanceExpression($builder, string $bindingType, $geometry, ?string $as = 'minimum_clearance')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_MinimumClearance', $as, $geometry);
    }

    public static function getMinimumClearanceLineExpression($builder, string $bindingType, $geometry, ?string $as = 'minimum_clearance_line')
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_MinimumClearanceLine', $as, $geometry);
    }
}
