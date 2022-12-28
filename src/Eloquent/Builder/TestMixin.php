<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Cast\BBoxCast;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class TestMixin
{
    public function mSelect()
    {
        /** @var EloquentBuilder|\Illuminate\Database\Query\Builder $this */

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
