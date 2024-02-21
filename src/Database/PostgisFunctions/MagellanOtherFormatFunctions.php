<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression;
use Clickbar\Magellan\Enums\GeojsonOutputOption;
use Clickbar\Magellan\Enums\GeometryType;
use Illuminate\Database\Query\Expression;

trait MagellanOtherFormatFunctions
{
    /**
     * Returns a geometry as a GeoJSON "geometry", or a row as a GeoJSON "feature". (See the GeoJSON specifications RFC 7946). 2D and 3D Geometries are both supported. GeoJSON only support SFS 1.1 geometry types (no curve support for example).
     *
     * @param  int|Expression|\Closure  $maximalDecimalDigits may be used to reduce the maximum number of decimal places used in output (defaults to 9). If you are using EPSG:4326 and are outputting the geometry only for display, maxdecimaldigits=6 can be a good choice for many maps.
     * @param  GeojsonOutputOption[]  $options
     *
     * @see https://postgis.net/docs/ST_AsGeoJSON.html
     */
    public static function asGeoJson($geometry, int|Expression|\Closure $maximalDecimalDigits = 9, array $options = [], GeometryType $geometryType = null): MagellanStringExpression
    {
        $optionalParameters = [$maximalDecimalDigits];
        if (! empty($options)) {
            $optionalParameters[] = collect($options)->reduce(fn (int $flags, GeojsonOutputOption $option) => $flags | $option->value, 0);
        }

        return MagellanBaseExpression::string('ST_AsGeoJSON', [GeoParam::wrap($geometry), ...$optionalParameters], $geometryType);
    }
}
