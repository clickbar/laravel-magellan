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
}
