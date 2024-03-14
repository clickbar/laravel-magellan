<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression;
use Clickbar\Magellan\Enums\GeometryType;
use Illuminate\Database\Query\Expression;

trait MagellanMeasurementFunctions
{
    /**
     * Returns minimum distance in meters between two lon/lat points. Uses a spherical earth and radius derived from the spheroid defined by the SRID. Faster than ST_DistanceSpheroid, but less accurate. PostGIS Versions prior to 1.5 only implemented for points.
     *
     *
     * @see https://postgis.net/docs/ST_DistanceSphere.html
     */
    public static function distanceSphere($geometryA, $geometryB): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_DistanceSphere', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * For geometry types returns the minimum 2D Cartesian (planar) distance between two geometries, in projected units (spatial ref units).
     * For geography types defaults to return the minimum geodetic distance between two geographies in meters, compute on the spheroid determined by the SRID. If use_spheroid is false, a faster spherical calculation is used.
     *
     *
     * @see https://postgis.net/docs/ST_Distance.html
     */
    public static function distance($geometryA, $geometryB, bool|Expression|\Closure|null $useSpheroid = null, ?GeometryType $geometryType = null): MagellanNumericExpression
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = GeometryType::Geography;
        }
        $useSpheroid = $useSpheroid ?? true;
        $optionalParamters = $geometryType === GeometryType::Geography ? [$useSpheroid] : [];

        return MagellanBaseExpression::numeric('ST_Distance', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), ...$optionalParamters], $geometryType);
    }

    /**
     * Returns minimum distance in meters between two lon/lat geometries given a particular spheroid. See the explanation of spheroids given for ST_LengthSpheroid.
     *
     *
     * @see https://postgis.net/docs/ST_Distance_Spheroid.html
     */
    public static function distanceSpheroid($geometryA, $geometryB, string|Expression|\Closure|null $measurementSpheroid = 'SPHEROID["WGS 84",6378137,298.257223563]'): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_DistanceSpheroid', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $measurementSpheroid]);
    }

    /**
     * Returns the 3-dimensional minimum cartesian distance between two geometries in projected units (spatial ref units).
     *
     *
     * @see https://postgis.net/docs/ST_3DDistance.html
     */
    public static function distance3D($geometryA, $geometryB): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_3DDistance', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Implements algorithm for computing the Fréchet distance restricted to discrete points for both geometries, based on Computing Discrete Fréchet Distance. The Fréchet distance is a measure of similarity between curves that takes into account the location and ordering of the points along the curves. Therefore it is often better than the Hausdorff distance.
     *
     * @param  float|Expression|\Closure|null  $densityFrac  When the optional densifyFrac is specified, this function performs a segment densification before computing the discrete Fréchet distance. The densifyFrac parameter sets the fraction by which to densify each segment. Each segment will be split into a number of equal-length subsegments, whose fraction of the total length is closest to the given fraction.
     *
     * @see https://postgis.net/docs/ST_FrechetDistance.html
     */
    public static function distanceFrechet($geometryA, $geometryB, float|Expression|\Closure|null $densityFrac = null): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_FrechetDistance', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $densityFrac]);
    }

    /**
     * Returns the Hausdorff distance between two geometries. The Hausdorff distance is a measure of how similar or dissimilar 2 geometries are.
     *
     * @param  float|Expression|\Closure|null  $densityFrac  The densifyFrac parameter can be specified, to provide a more accurate answer by densifying segments before computing the discrete Hausdorff distance. Each segment is split into a number of equal-length subsegments whose fraction of the segment length is closest to the given fraction.
     *
     * @see https://postgis.net/docs/ST_HausdorffDistance.html
     */
    public static function distanceHausdorff($geometryA, $geometryB, float|Expression|\Closure|null $densityFrac = null): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_HausdorffDistance', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $densityFrac]);
    }

    /**
     * Returns the 2-dimensional maximum distance between two geometries, in projected units. The maximum distance always occurs between two vertices. This is the length of the line returned by ST_LongestLine.
     *
     *
     * @see https://postgis.net/docs/ST_MaxDistance.html
     */
    public static function maxDistance($geometryA, $geometryB): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_MaxDistance', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 3-dimensional maximum cartesian distance between two geometries in projected units (spatial ref units).
     *
     *
     * @see https://postgis.net/docs/ST_3DMaxDistance.html
     */
    public static function maxDistance3D($geometryA, $geometryB): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_3DMaxDistance', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the area of a polygonal geometry. For geometry types a 2D Cartesian (planar) area is computed, with units specified by the SRID. For geography types by default area is determined on a spheroid with units in square meters. To compute the area using the faster but less accurate spherical model use ST_Area(geog,false).
     *
     *
     * @see https://postgis.net/docs/ST_Area.html
     */
    public static function area($geometry, bool|Expression|\Closure|null $useSpheroid = null, ?GeometryType $geometryType = null): MagellanNumericExpression
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = GeometryType::Geography;
        }
        $useSpheroid = $useSpheroid ?? true;
        $optionalParamters = $geometryType === GeometryType::Geography ? [$useSpheroid] : [];

        return MagellanBaseExpression::numeric('ST_Area', [GeoParam::wrap($geometry), ...$optionalParamters], $geometryType);
    }

    /**
     * For geometry types: returns the 2D Cartesian length of the geometry if it is a LineString, MultiLineString, ST_Curve, ST_MultiCurve. For areal geometries 0 is returned; use ST_Perimeter instead. The units of length is determined by the spatial reference system of the geometry.
     * For geography types: computation is performed using the inverse geodetic calculation. Units of length are in meters. If PostGIS is compiled with PROJ version 4.8.0 or later, the spheroid is specified by the SRID, otherwise it is exclusive to WGS84. If use_spheroid=false, then the calculation is based on a sphere instead of a spheroid.
     *
     *
     * @see https://postgis.net/docs/ST_Length.html
     */
    public static function length($geometry, bool|Expression|\Closure|null $useSpheroid = null, ?GeometryType $geometryType = null): MagellanNumericExpression
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = GeometryType::Geography;
        }
        $useSpheroid = $useSpheroid ?? true;
        $optionalParamters = $geometryType === GeometryType::Geography ? [$useSpheroid] : [];

        return MagellanBaseExpression::numeric('ST_Length', [GeoParam::wrap($geometry), ...$optionalParamters], $geometryType);
    }

    /**
     * Returns the 3-dimensional or 2-dimensional length of the geometry if it is a LineString or MultiLineString. For 2-d lines it will just return the 2-d length (same as ST_Length and ST_Length2D)
     *
     *
     * @see https://postgis.net/docs/ST_3DLength.html
     */
    public static function length3D($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_3DLength', [GeoParam::wrap($geometry)]);
    }

    /**
     * Calculates the length or perimeter of a geometry on an ellipsoid. This is useful if the coordinates of the geometry are in longitude/latitude and a length is desired without reprojection. The spheroid is specified by a text value as follows:
     *
     *
     * @see https://postgis.net/docs/ST_Length_Spheroid.html
     */
    public static function lengthSpheroid($geometry, string|Expression|\Closure|null $spheroid = 'SPHEROID["WGS 84",6378137,298.257223563]'): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_LengthSpheroid', [GeoParam::wrap($geometry), $spheroid]);
    }

    /**
     * Returns the 2-dimensional point on geom1 that is closest to geom2. This is the first point of the shortest line between the geometries (as computed by ST_ShortestLine).
     *
     *
     * @see https://postgis.net/docs/ST_ClosestPoint.html
     */
    public static function closestPoint($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ClosestPoint', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 3-dimensional point on g1 that is closest to g2. This is the first point of the 3D shortest line. The 3D length of the 3D shortest line is the 3D distance.
     *
     *
     * @see https://postgis.net/docs/ST_3DClosestPoint.html
     */
    public static function closestPoint3D($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_3DClosestPoint', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 2-dimensional longest line between the points of two geometries. The line returned starts on g1 and ends on g2.
     * The longest line always occurs between two vertices. The function returns the first longest line if more than one is found. The length of the line is equal to the distance returned by ST_MaxDistance.
     * If g1 and g2 are the same geometry, returns the line between the two vertices farthest apart in the geometry. This is a diameter of the circle computed by ST_MinimumBoundingCircle
     *
     *
     * @see https://postgis.net/docs/ST_LongestLine.html
     */
    public static function longestLine($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_LongestLine', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 3-dimensional longest line between two geometries. The function returns the first longest line if more than one. The line returned starts in g1 and ends in g2. The 3D length of the line is equal to the distance returned by ST_3DMaxDistance.
     *
     *
     * @see https://postgis.net/docs/ST_3DLongestLine.html
     */
    public static function longestLine3D($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_3DLongestLine', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 2-dimensional shortest line between two geometries. The line returned starts in geom1 and ends in geom2. If geom1 and geom2 intersect the result is a line with start and end at an intersection point. The length of the line is the same as ST_Distance returns for g1 and g2.
     *
     *
     * @see https://postgis.net/docs/ST_ShortestLine.html
     */
    public static function shortestLine($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ShortestLine', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 3-dimensional shortest line between two geometries. The function will only return the first shortest line if more than one, that the function finds. If g1 and g2 intersects in just one point the function will return a line with both start and end in that intersection-point. If g1 and g2 are intersecting with more than one point the function will return a line with start and end in the same point but it can be any of the intersecting points. The line returned will always start in g1 and end in g2. The 3D length of the line this function returns will always be the same as ST_3DDistance returns for g1 and g2.
     *
     *
     * @see https://postgis.net/docs/ST_3DShortestLine.html
     */
    public static function shortestLine3D($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_3DShortestLine', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns the 2D perimeter of the geometry/geography if it is a ST_Surface, ST_MultiSurface (Polygon, MultiPolygon). 0 is returned for non-areal geometries. For linear geometries use ST_Length. For geometry types, units for perimeter measures are specified by the spatial reference system of the geometry.
     * For geography types, the calculations are performed using the inverse geodetic problem, where perimeter units are in meters. If PostGIS is compiled with PROJ version 4.8.0 or later, the spheroid is specified by the SRID, otherwise it is exclusive to WGS84. If use_spheroid=false, then calculations will approximate a sphere instead of a spheroid.
     *
     *
     * @see https://postgis.net/docs/ST_Perimeter.html
     */
    public static function perimeter($geometry, bool|Expression|\Closure|null $useSpheroid = null, ?GeometryType $geometryType = null): MagellanNumericExpression
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = GeometryType::Geography;
        }
        $useSpheroid = $useSpheroid ?? true;
        $optionalParamters = $geometryType === GeometryType::Geography ? [$useSpheroid] : [];

        return MagellanBaseExpression::numeric('ST_Perimeter', [GeoParam::wrap($geometry), ...$optionalParamters], $geometryType);
    }

    /**
     * Returns the 3-dimensional perimeter of the geometry, if it is a polygon or multi-polygon. If the geometry is 2-dimensional, then the 2-dimensional perimeter is returned.
     *
     *
     * @see https://postgis.net/docs/ST_3DPerimeter.html
     */
    public static function perimeter3D($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_3DPerimeter', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the azimuth in radians of the target point from the origin point, or NULL if the two points are coincident. The azimuth angle is a positive clockwise angle referenced from the positive Y axis (geometry) or the North meridian (geography): North = 0; Northeast = π/4; East = π/2; Southeast = 3π/4; South = π; Southwest 5π/4; West = 3π/2; Northwest = 7π/4.
     * For the geography type, the azimuth solution is known as the inverse geodetic problem.
     * The azimuth is a mathematical concept defined as the angle between a reference vector and a point, with angular units in radians. The result value in radians can be converted to degrees using the PostgreSQL function degrees().
     * Azimuth can be used in conjunction with ST_Translate to shift an object along its perpendicular axis. See the upgis_lineshift() function in the PostGIS wiki for an implementation of this.
     *
     *
     * @see https://postgis.net/docs/ST_Azimuth.html
     */
    public static function azimuth($geometryA, $geometryB, ?GeometryType $geometryType = null): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Azimuth', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)], $geometryType);
    }

    /**
     * Computes the clockwise angle between two vectors.
     * Variant 1: computes the angle enclosed by the points P1-P2-P3. If a 4th point provided computes the angle points P1-P2 and P3-P4
     * Variant 2: computes the angle between two vectors S1-E1 and S2-E2, defined by the start and end points of the input lines
     * The result is a positive angle between 0 and 2π radians. The radian result can be converted to degrees using the PostgreSQL function degrees().
     * Note that ST_Angle(P1,P2,P3) = ST_Angle(P2,P1,P2,P3).
     *
     * @param  mixed  $point4
     *
     * @see https://postgis.net/docs/ST_Angle.html
     */
    public static function angleFromPoints($point1, $point2, $point3, $point4 = null): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Angle', [GeoParam::wrap($point1), GeoParam::wrap($point2), GeoParam::wrap($point3), GeoParam::wrap($point4)]);
    }

    /**
     * Computes the clockwise angle between two vectors.
     * Variant 1: computes the angle enclosed by the points P1-P2-P3. If a 4th point provided computes the angle points P1-P2 and P3-P4
     * Variant 2: computes the angle between two vectors S1-E1 and S2-E2, defined by the start and end points of the input lines
     * The result is a positive angle between 0 and 2π radians. The radian result can be converted to degrees using the PostgreSQL function degrees().
     * Note that ST_Angle(P1,P2,P3) = ST_Angle(P2,P1,P2,P3).
     *
     *
     * @see https://postgis.net/docs/ST_Angle.html
     */
    public static function angleFromLines($lineString1, $lineString2): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Angle', [GeoParam::wrap($lineString1), GeoParam::wrap($lineString2)]);
    }

    /**
     * Returns a point projected from a start point along a geodetic using a given distance and azimuth (bearing). This is known as the direct geodetic problem.
     * The distance is given in meters. Negative values are supported.
     * The azimuth (also known as heading or bearing) is given in radians. It is measured clockwise from true north (azimuth zero). East is azimuth π/2 (90 degrees); south is azimuth π (180 degrees); west is azimuth 3π/2 (270 degrees). Negative azimuth values and values greater than 2π (360 degrees) are supported.
     *
     *
     * @see https://postgis.net/docs/ST_Project.html
     */
    public static function project($geography, float|Expression|\Closure $distance, $azimuth): MagellanGeometryExpression
    {
        // TODO: consider returning geometry/geography
        return MagellanBaseExpression::geometry('ST_Project', [GeoParam::wrap($geography), $distance, $azimuth], GeometryType::Geography);
    }

    /**
     * It is possible for a geometry to meet the criteria for validity according to ST_IsValid (polygons) or ST_IsSimple (lines), but to become invalid if one of its vertices is moved by a small distance. This can happen due to loss of precision during conversion to text formats (such as WKT, KML, GML, GeoJSON), or binary formats that do not use double-precision floating point coordinates (e.g. MapInfo TAB).
     * The minimum clearance is a quantitative measure of a geometry's robustness to change in coordinate precision. It is the largest distance by which vertices of the geometry can be moved without creating an invalid geometry. Larger values of minimum clearance indicate greater robustness.
     * If a geometry has a minimum clearance of e, then:
     *  - No two distinct vertices in the geometry are closer than the distance e.
     *  - No vertex is closer than e to a line segement of which it is not an endpoint.
     * If no minimum clearance exists for a geometry (e.g. a single point, or a MultiPoint whose points are identical), the return value is Infinity.
     * To avoid validity issues caused by precision loss, ST_ReducePrecision can reduce coordinate precision while ensuring that polygonal geometry remains valid.
     *
     *
     * @see https://postgis.net/docs/ST_MinimumClearance.html
     */
    public static function minimumClearance($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_MinimumClearance', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the two-point LineString spanning a geometry's minimum clearance. If the geometry does not have a minimum clearance, LINESTRING EMPTY is returned.
     *
     *
     * @see https://postgis.net/docs/ST_MinimumClearanceLine.html
     */
    public static function minimumClearanceLine($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_MinimumClearanceLine', [GeoParam::wrap($geometry)]);
    }
}
