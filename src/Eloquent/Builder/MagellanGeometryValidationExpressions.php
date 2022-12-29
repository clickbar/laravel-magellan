<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanBooleanExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanSetExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanStringExpression;
use Clickbar\Magellan\Enums\MakeValidMethod;
use Clickbar\Magellan\Enums\ValidFlag;
use Illuminate\Support\Str;

trait MagellanGeometryValidationExpressions
{
    /**
     * Tests if an ST_Geometry value is well-formed and valid in 2D according to the OGC rules. For geometries with 3 and 4 dimensions, the validity is still only tested in 2 dimensions. For geometries that are invalid, a PostgreSQL NOTICE is emitted providing details of why it is not valid.
     * For the version with the flags parameter, supported values are documented in ST_IsValidDetail This version does not print a NOTICE explaining invalidity.
     *
     * @param $geometry
     * @param  ValidFlag|null  $validFlag
     * @return MagellanBooleanExpression
     *
     * @see https://postgis.net/docs/ST_IsValid.html
     */
    public static function isValid($geometry, ?ValidFlag $validFlag = null): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsValid', [$geometry], [$validFlag?->value]);
    }

    /**
     * Returns a valid_detail row, containing a boolean (valid) stating if a geometry is valid, a varchar (reason) stating a reason why it is invalid and a geometry (location) pointing out where it is invalid.
     * Useful to improve on the combination of ST_IsValid and ST_IsValidReason to generate a detailed report of invalid geometries.
     * The optional flags parameter is a bitfield. It can have the following values:
     * - 0: Use usual OGC SFS validity semantics.
     * - 1: Consider certain kinds of self-touching rings (inverted shells and exverted holes) as valid. This is also known as "the ESRI flag", since this is the validity model used by those tools. Note that this is invalid under the OGC model.
     *
     * @param $geometry
     * @param  ValidFlag|null  $validFlag
     * @return MagellanSetExpression
     *
     * @see https://postgis.net/docs/ST_IsValidDetail.html
     */
    public static function isValidDetail($geometry, ?ValidFlag $validFlag = null): MagellanSetExpression
    {
        return MagellanBaseExpression::set('ST_IsValidDetail', [$geometry], [$validFlag?->value]);
    }

    /**
     * Returns text stating if a geometry is valid, or if invalid a reason why.
     * Useful in combination with ST_IsValid to generate a detailed report of invalid geometries and reason
     *
     * @param $geometry
     * @param  ValidFlag|null  $validFlag
     * @return MagellanStringExpression
     *
     * @see https://postgis.net/docs/ST_IsValidReason.html
     */
    public static function isValidReason($geometry, ?ValidFlag $validFlag = null): MagellanStringExpression
    {
        return MagellanBaseExpression::string('ST_IsValidReason', [$geometry], [$validFlag?->value]);
    }

    /**
     * The function attempts to create a valid representation of a given invalid geometry without losing any of the input vertices. Valid geometries are returned unchanged.
     * Supported inputs are: POINTS, MULTIPOINTS, LINESTRINGS, MULTILINESTRINGS, POLYGONS, MULTIPOLYGONS and GEOMETRYCOLLECTIONS containing any mix of them.
     * In case of full or partial dimensional collapses, the output geometry may be a collection of lower-to-equal dimension geometries, or a geometry of lower dimension.
     * Single polygons may become multi-geometries in case of self-intersections.
     * The params argument can be used to supply an options string to select the method to use for building valid geometry. The options string is in the format "method=linework|structure keepcollapsed=true|false".
     *
     *
     * @param $geometry
     * @param  MakeValidMethod|null  $makeValidMethod
     * @param  bool|null  $keepCollapsed The "keepcollapsed" key is only valid for the "structure" algorithm, and takes a value of "true" or "false". When set to "false", geometry components that collapse to a lower dimensionality, for example a one-point linestring would be dropped.
     * @return MagellanGeometryExpression
     *
     * @see https://postgis.net/docs/ST_MakeValid.html
     */
    public static function makeValid($geometry, ?MakeValidMethod $makeValidMethod = null, ?bool $keepCollapsed = null): MagellanGeometryExpression
    {
        $params = [
            "method=$makeValidMethod?->value",
            "keepcollapsed=$keepCollapsed",
        ];

        $styleParameter = collect($params)
            ->filter(fn ($part) => ! Str::endsWith($part, 'null'))
            ->join(' ');
        if (empty($styleParameter)) {
            $styleParameter = null;
        }

        return MagellanBaseExpression::geometry('ST_MakeValid', [$geometry], [$styleParameter]);
    }
}
