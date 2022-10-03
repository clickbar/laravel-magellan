<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Query\Expression;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class PostgisMeasurementBuilderMacros
{
    /*
     * ST_DistanceSphere
     */

    public function selectDistanceSphere(): Closure
    {
        return function ($a, $b, string $as = 'distance_sphere'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getDistanceSphereExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereDistanceSphere(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistanceSphereExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByDistanceSphere(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getDistanceSphereExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
     * ST_Distance
     */

    public function selectDistance(): Closure
    {
        return function ($a, $b, ?bool $useSpheroid = null, string $as = 'distance', ?string $type = null): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getDistanceExpression($this, 'select', $a, $b, $useSpheroid, $as, $type));
        };
    }

    public function whereDistance(): Closure
    {
        return function ($a, $b, $operator = null, $value = null, ?bool $useSpheroid = null, ?string $type = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistanceExpression($this, 'where', $a, $b, $useSpheroid, null, $type),
                $operator,
                $value,
            );
        };
    }

    public function orderByDistance(): Closure
    {
        return function ($a, $b, string $direction = 'ASC', ?bool $useSpheroid = null, ?string $type = null): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getDistanceExpression($this, 'order', $a, $b, $useSpheroid, null, $type),
                $direction
            );
        };
    }

    /*
     * ST_DistanceSpheroid
     * TODO: Consider other default handling of spheroid
     */

    public function selectDistanceSpheroid(): Closure
    {
        return function ($a, $b, ?string $measurementSpheroid = 'SPHEROID["WGS 84",6378137,298.257223563]', string $as = 'distance_spheroid'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getDistanceSpheroidExpression($this, 'select', $a, $b, $measurementSpheroid, $as));
        };
    }

    public function whereDistanceSpheroid(): Closure
    {
        return function ($a, $b, $operator = null, $value = null, ?string $measurementSpheroid = 'SPHEROID["WGS 84",6378137,298.257223563]'): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistanceSpheroidExpression($this, 'where', $a, $b, $measurementSpheroid, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByDistanceSpheroid(): Closure
    {
        return function ($a, $b, ?string $measurementSpheroid = 'SPHEROID["WGS 84",6378137,298.257223563]', string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getDistanceSpheroidExpression($this, 'order', $a, $b, $measurementSpheroid, null),
                $direction
            );
        };
    }

    /*
     * ST_3DDistance
     */

    public function selectDistance3d(): Closure
    {
        return function ($a, $b, string $as = 'distance_3d'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getDistance3dExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereDistance3d(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistance3dExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByDistance3d(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getDistance3dExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
     * ST_FrechetDistance
     */

    public function selectDistanceFrechet(): Closure
    {
        return function ($a, $b, ?float $desityFrac = -1, string $as = 'distance_frechet'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getDistanceFrechetExpression($this, 'select', $a, $b, $desityFrac, $as));
        };
    }

    public function whereDistanceFrechet(): Closure
    {
        return function ($a, $b, $operator = null, $value = null, ?float $desityFrac = -1): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistanceFrechetExpression($this, 'where', $a, $b, $desityFrac, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByDistanceFrechet(): Closure
    {
        return function ($a, $b, ?float $desityFrac = -1, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getDistanceFrechetExpression($this, 'order', $a, $b, $desityFrac, null),
                $direction
            );
        };
    }

    /*
     * ST_HausdorffDistance
     */

    public function selectDistanceHausdorff(): Closure
    {
        return function ($a, $b, ?float $desityFrac = null, string $as = 'distance_frechet'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getDistanceHausdorffExpression($this, 'select', $a, $b, $desityFrac, $as));
        };
    }

    public function whereDistanceHausdorff(): Closure
    {
        return function ($a, $b, $operator = null, $value = null, ?float $desityFrac = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistanceHausdorffExpression($this, 'where', $a, $b, $desityFrac, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByDistanceHausdorff(): Closure
    {
        return function ($a, $b, ?float $desityFrac = null, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getDistanceHausdorffExpression($this, 'order', $a, $b, $desityFrac, null),
                $direction
            );
        };
    }

    /*
    * ST_MaxDistance
    */

    public function selectMaxDistance(): Closure
    {
        return function ($a, $b, string $as = 'max_distance'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getMaxDistanceExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereMaxDistance(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getMaxDistanceExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByMaxDistance(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getMaxDistanceExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
     * ST_3DMaxDistance
     */

    public function selectMaxDistance3d(): Closure
    {
        return function ($a, $b, string $as = 'max_distance_3d'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getMaxDistance3dExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereMaxDistance3d(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getMaxDistance3dExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByMaxDistance3d(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getMaxDistance3dExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
     * ST_Area
     */

    public function selectArea(): Closure
    {
        return function ($a, ?bool $useSpheroid = null, ?string $type = null, string $as = 'area'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getAreaExpression($this, 'select', $a, $useSpheroid, $as, $type));
        };
    }

    public function whereArea(): Closure
    {
        return function ($a, $operator = null, $value = null, ?bool $useSpheroid = null, ?string $type = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getAreaExpression($this, 'where', $a, $useSpheroid, null, $type),
                $operator,
                $value,
            );
        };
    }

    public function orderByArea(): Closure
    {
        return function ($a, ?bool $useSpheroid = null, ?string $type = null, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getAreaExpression($this, 'order', $a, $useSpheroid, null, $type),
                $direction
            );
        };
    }

    /*
     * ST_Length
     */

    public function selectLength(): Closure
    {
        return function ($lineString2d, ?bool $useSpheroid = null, ?string $type = null, string $as = 'length'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getLengthExpression($this, 'select', $lineString2d, $useSpheroid, $as, $type));
        };
    }

    public function whereLength(): Closure
    {
        return function ($lineString2d, $operator = null, $value = null, ?bool $useSpheroid = null, ?string $type = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getLengthExpression($this, 'where', $lineString2d, $useSpheroid, null, $type),
                $operator,
                $value,
            );
        };
    }

    public function orderByLength(): Closure
    {
        return function ($lineString2d, ?bool $useSpheroid = null, ?string $type = null, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getLengthExpression($this, 'order', $lineString2d, $useSpheroid, null, $type),
                $direction
            );
        };
    }

    /*
     * ST_3DLength
     */

    public function selectLength3d(): Closure
    {
        return function ($lineString3d, string $as = 'area'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getLength3dExpression($this, 'select', $lineString3d, $as));
        };
    }

    public function whereLength3d(): Closure
    {
        return function ($lineString3d, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getLength3dExpression($this, 'where', $lineString3d, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByLength3d(): Closure
    {
        return function ($lineString3d, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getLength3dExpression($this, 'order', $lineString3d, null),
                $direction
            );
        };
    }

    /*
     * ST_LengthSpheroid
     */

    public function selectLengthSpheroid(): Closure
    {
        return function ($a, ?string $spheroid = 'SPHEROID["WGS 84",6378137,298.257223563]', string $as = 'length_spheroid'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getLengthSpheroidExpression($this, 'select', $a, $spheroid, $as));
        };
    }

    public function whereLengthSpheroid(): Closure
    {
        return function ($a, $operator = null, $value = null, ?string $spheroid = 'SPHEROID["WGS 84",6378137,298.257223563]'): Builder {
            return $this->where(PostgisMeasurementExpressions::getLengthSpheroidExpression($this, 'where', $a, $spheroid, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByLengthSpheroid(): Closure
    {
        return function ($a, ?string $spheroid = 'SPHEROID["WGS 84",6378137,298.257223563]', string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getLengthSpheroidExpression($this, 'order', $a, $spheroid, null),
                $direction
            );
        };
    }

    /*
     * ST_ClosestPoint
     */

    public function selectClosestPoint(): Closure
    {
        return function ($a, $b, string $as = 'closest_point'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getClosestPointExpression($this, 'select', $a, $b, $as));
        };
    }

    /*
    TODO: Where value is point (geometry) => default laravel where function may not serialize out point class the wright way
     ==> CHECK ME. Seems to work because "Geometry = XXX" infers XXC to be Geometry and applies automatic casting
    */
    public function whereClosestPoint(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getClosestPointExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByClosestPoint(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getClosestPointExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
    * ST_3DClosestPoint
    */

    public function selectClosestPoint3d(): Closure
    {
        return function ($a, $b, string $as = 'closest_point_3d'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getClosestPoint3dExpression($this, 'select', $a, $b, $as));
        };
    }

    /*
    TODO: Where value is point (geometry) => default laravel where function may not serialize out point class the wright way
     ==> CHECK ME. Seems to work because "Geometry = XXX" infers XXC to be Geometry and applies automatic casting
    */
    public function whereClosestPoint3d(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getClosestPoint3dExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByClosestPoint3d(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getClosestPoint3dExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
    * ST_LongestLine
    */

    public function selectLongestLine(): Closure
    {
        return function ($a, $b, string $as = 'longest_line'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getLongestLineExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereLongestLine(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getLongestLineExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByLongestLine(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getLongestLineExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
    * ST_3DLongestLine
    */

    public function selectLongestLine3d(): Closure
    {
        return function ($a, $b, string $as = 'longest_line_3d'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getLongestLineExpression3d($this, 'select', $a, $b, $as));
        };
    }

    public function whereLongestLine3d(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getLongestLineExpression3d($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByLongestLine3d(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getLongestLineExpression3d($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
    * ST_ShortestLine
    */

    public function selectShortestLine(): Closure
    {
        return function ($a, $b, string $as = 'shortest_line'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getShortestLineExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereShortestLine(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getShortestLineExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByShortestLine(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getShortestLineExpression($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
    * ST_3DShortestLine
    */

    public function selectShortestLine3d(): Closure
    {
        return function ($a, $b, string $as = 'shortest_line_3d'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getShortestLineExpression3d($this, 'select', $a, $b, $as));
        };
    }

    public function whereShortestLine3d(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getShortestLineExpression3d($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByShortestLine3d(): Closure
    {
        return function ($a, $b, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getShortestLineExpression3d($this, 'order', $a, $b, null),
                $direction
            );
        };
    }

    /*
     * ST_Perimeter
     */

    public function selectPerimeter(): Closure
    {
        return function ($geo, ?bool $useSpheroid = null, ?string $type = null, string $as = 'perimeter'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getPerimeterExpression($this, 'select', $geo, $useSpheroid, $as, $type));
        };
    }

    public function wherePerimeter(): Closure
    {
        return function ($geo, $operator = null, $value = null, ?bool $useSpheroid = null, ?string $type = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getPerimeterExpression($this, 'where', $geo, $useSpheroid, null, $type),
                $operator,
                $value,
            );
        };
    }

    public function orderByPerimeter(): Closure
    {
        return function ($geo, ?bool $useSpheroid = null, ?string $type = null, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getPerimeterExpression($this, 'order', $geo, $useSpheroid, null, $type),
                $direction
            );
        };
    }

    /*
     * ST_3DPerimeter
     */

    public function selectPerimeter3d(): Closure
    {
        return function ($geo, string $as = 'perimeter_3d'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getPerimeter3dExpression($this, 'select', $geo, $as));
        };
    }

    public function wherePerimeter3d(): Closure
    {
        return function ($geo, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getPerimeter3dExpression($this, 'where', $geo, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByPerimeter3d(): Closure
    {
        return function ($geo, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getPerimeter3dExpression($this, 'order', $geo, null),
                $direction
            );
        };
    }

    /*
     * ST_Azimuth
     */

    public function selectAzimuth(): Closure
    {
        return function ($a, $b, string $as = 'azimuth', ?string $type = null): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getAzimuthExpression($this, 'select', $a, $b, $as, $type));
        };
    }

    public function whereAzimuth(): Closure
    {
        return function ($a, $b, $operator = null, $value = null, ?string $type = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getAzimuthExpression($this, 'where', $a, $b, null, $type),
                $operator,
                $value,
            );
        };
    }

    public function orderByAzimuth(): Closure
    {
        return function ($a, $b, string $direction = 'ASC', ?string $type = null): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getAzimuthExpression($this, 'order', $a, $b, null, $type),
                $direction
            );
        };
    }

    /*
     * ST_Angle
     */

    public function selectAngleFromPoints(): Closure
    {
        return function ($p1, $p2, $p3, $p4 = null, string $as = 'angle'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getAngleFromPointsExpression($this, 'select', $p1, $p2, $p3, $p4, $as));
        };
    }

    public function whereAngleFromPoints(): Closure
    {
        return function ($p1, $p2, $p3, $p4 = null, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getAngleFromPointsExpression($this, 'where', $p1, $p2, $p3, $p4, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByAngleFromPoints(): Closure
    {
        return function ($p1, $p2, $p3, $p4 = null, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getAngleFromPointsExpression($this, 'order', $p1, $p2, $p3, $p4, null),
                $direction
            );
        };
    }

    public function selectAngleFromLines(): Closure
    {
        return function ($lineString1, $lineString2, string $as = 'angle'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getAngleFromLinesExpression($this, 'select', $lineString1, $lineString2, $as));
        };
    }

    public function whereAngleFromLines(): Closure
    {
        return function ($lineString1, $lineString2, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getAngleFromLinesExpression($this, 'where', $lineString1, $lineString2, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByAngleFromLines(): Closure
    {
        return function ($lineString1, $lineString2, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getAngleFromLinesExpression($this, 'order', $lineString1, $lineString2, null),
                $direction
            );
        };
    }

    /*
     * ST_Project
     */

    public function selectProject(): Closure
    {
        return function ($geography, float $distance, float|Expression $azimuth, string $as = 'project'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getProjectExpression($this, 'select', $geography, $distance, $azimuth, $as));
        };
    }

    public function whereProject(): Closure
    {
        return function ($geography, float $distance, float|Expression $azimuth, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getProjectExpression($this, 'where', $geography, $distance, $azimuth, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByProject(): Closure
    {
        return function ($geography, float $distance, float|Expression $azimuth, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getProjectExpression($this, 'order', $geography, $distance, $azimuth, null),
                $direction
            );
        };
    }

    /*
     * ST_MinimumClearance
     */

    public function selectMinimumClearance(): Closure
    {
        return function ($geometry, string $as = 'minimum_clearance'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getMinimumClearanceExpression($this, 'select', $geometry, $as));
        };
    }

    public function whereMinimumClearance(): Closure
    {
        return function ($geometry, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getMinimumClearanceExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByMinimumClearance(): Closure
    {
        return function ($geometry, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getMinimumClearanceExpression($this, 'order', $geometry, null),
                $direction
            );
        };
    }

    /*
     * ST_MinimumClearanceLine
     */

    public function selectMinimumClearanceLine(): Closure
    {
        return function ($geometry, string $as = 'minimum_clearance_line'): Builder {
            return $this->addSelect(PostgisMeasurementExpressions::getMinimumClearanceLineExpression($this, 'select', $geometry, $as));
        };
    }

    public function whereMinimumClearanceLine(): Closure
    {
        return function ($geometry, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getMinimumClearanceLineExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByMinimumClearanceLine(): Closure
    {
        return function ($geometry, string $direction = 'ASC'): Builder {
            return $this->orderBy(
                PostgisMeasurementExpressions::getMinimumClearanceLineExpression($this, 'order', $geometry, null),
                $direction
            );
        };
    }
}
