<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Boxes\Box;
use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class BBoxCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return Box2D|Box3D|null
     */
    public function get($model, string $key, mixed $value, array $attributes)
    {
        if (! isset($value)) {
            return null;
        }

        $argument = Str::between($value, '(', ')');

        $floats = Str::of($argument)->split('/[\s,]+/')->map(fn ($item) => floatval($item));

        if ($floats->count() !== 4 && $floats->count() !== 6) {
            return null;
        }

        $is3d = Str::startsWith($value, 'BOX3D');
        if ($is3d) {
            return Box3D::make($floats[0], $floats[1], $floats[2], $floats[3], $floats[4], $floats[5]);
        }

        return Box2D::make($floats[0], $floats[1], $floats[2], $floats[3]);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        if ($value instanceof Box) {
            return $value->toString();
        }

        return $value;
    }
}
