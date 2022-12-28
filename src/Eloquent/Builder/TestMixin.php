<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Cast\BBoxCast;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

/** @mixin Builder */
class TestMixin
{
    public function mSelect()
    {
        return function (MagellanExpression $magellanExpression, string $as = null) {
            $asOrDefault = $as ?? $magellanExpression->getDefaultAs();

            if ($magellanExpression->returnsBbox() && $this instanceof EloquentBuilder) {
                $this->withCasts([$asOrDefault => BBoxCast::class]);
            }

            return $this->addSelect($magellanExpression->invoke($this, 'select', $asOrDefault));
        };
    }

    public function mOrderBy()
    {
        return function (MagellanExpression $magellanExpression, string $direction = 'ASC') {
            return $this->addSelect($magellanExpression->invoke($this, 'order'));
        };
    }
}
