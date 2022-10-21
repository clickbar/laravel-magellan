<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Closure;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class PostgisBoundingBoxBuilderMacros
{
    /*
     * Box2D
     */

    public function selectBox2D(): Closure
    {
        return function ($a, string $as = 'box2d'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getBox2DExpression($this, 'select', $a, $as));
        };
    }

    public function whereBox2D(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getBox2DExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * Box3D
     */

    public function selectBox3D(): Closure
    {
        return function ($a, string $as = 'box3d'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getBox3DExpression($this, 'select', $a, $as));
        };
    }

    public function whereBox3D(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getBox3DExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_Extent
     */

    public function selectExtent(): Closure
    {
        return function ($a, string $as = 'extent'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getExtentExpression($this, 'select', $a, $as));
        };
    }

    public function whereExtend(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getExtentExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_3DExtent
     */

    public function select3DExtent(): Closure
    {
        return function ($a, string $as = 'extent3D'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::get3DExtentExpression($this, 'select', $a, $as));
        };
    }

    public function where3DExtent(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::get3DExtentExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_MakeBox2D
     */

    public function selectMakeBox2D(): Closure
    {
        return function ($a, $b, string $as = 'box2d'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getMakeBox2DExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereMakeBox2D(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getMakeBox2DExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_3DMakeBox
     */

    public function selectMakeBox3D(): Closure
    {
        return function ($a, $b, string $as = 'box3d'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getMakeBox3DExpression($this, 'select', $a, $b, $as));
        };
    }

    public function whereMakeBox3D(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getMakeBox3DExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_XMax
     */

    public function selectXMax(): Closure
    {
        return function ($a, string $as = 'xmax'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getXMaxExpression($this, 'select', $a, $as));
        };
    }

    public function whereXMax(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getXMaxExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_XMin
     */

    public function selectXMin(): Closure
    {
        return function ($a, string $as = 'xmin'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getXMinExpression($this, 'select', $a, $as));
        };
    }

    public function whereXMin(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getXMinExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
   * ST_YMax
   */

    public function selectYMax(): Closure
    {
        return function ($a, string $as = 'ymax'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getYMaxExpression($this, 'select', $a, $as));
        };
    }

    public function whereYMax(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getYMaxExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_YMin
     */

    public function selectYMin(): Closure
    {
        return function ($a, string $as = 'ymin'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getYMinExpression($this, 'select', $a, $as));
        };
    }

    public function whereYMin(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getYMinExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
  * ST_YMax
  */

    public function selectZMax(): Closure
    {
        return function ($a, string $as = 'zmax'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getZMaxExpression($this, 'select', $a, $as));
        };
    }

    public function whereZMax(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getZMaxExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_ZMin
     */

    public function selectZMin(): Closure
    {
        return function ($a, string $as = 'zmin'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getZMinExpression($this, 'select', $a, $as));
        };
    }

    public function whereZMin(): Closure
    {
        return function ($a, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getZMinExpression($this, 'where', $a, null),
                $operator,
                $value,
            );
        };
    }

    /*
    * ST_Expand
    */

    public function selectExpand(): Closure
    {
        return function ($a, ?float $unitsToExpand = null, ?float $dx = null, ?float $dy = null, ?float $dz = null, ?float $dm = null, string $as = 'expand'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getExpandExpression($this, 'select', $a, $as, $unitsToExpand, $dx, $dy, $dz, $dm));
        };
    }

    public function whereExpand(): Closure
    {
        return function ($a, ?float $unitsToExpand = null, ?float $dx = null, ?float $dy = null, ?float $dz = null, ?float $dm = null, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getExpandExpression($this, 'where', $a, null, $unitsToExpand, $dx, $dy, $dz, $dm),
                $operator,
                $value);
        };
    }

    /*
    * ST_EstimatedExtent
    */

    public function selectEstimatedExtent(): Closure
    {
        return function (string $tableName, string $geoColumn, ?string $schemaName = null, ?bool $parentOnly = null, string $as = 'estimated_extent'): self {
            return $this->addSelect(PostgisBoundingBoxExpressions::getEstimatedExtentExpression($this, 'select', $as, $tableName, $geoColumn, $schemaName, $parentOnly));
        };
    }

    public function whereEstimatedExtent(): Closure
    {
        return function (string $tableName, string $geoColumn, ?string $schemaName = null, ?bool $parentOnly = null, $operator = null, $value = null): self {
            return $this->where(PostgisBoundingBoxExpressions::getEstimatedExtentExpression($this, 'where', null, $tableName, $geoColumn, $schemaName, $parentOnly),
                $operator,
                $value);
        };
    }
}
