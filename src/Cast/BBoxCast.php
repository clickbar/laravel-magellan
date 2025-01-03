<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Boxes\Box;
use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @internal Use the specific Box (sub)class as caster in the model e.g. Box2D::class or Box3D::class
 *
 * @template T of Box
 *
 * @implements CastsAttributes<T,T>
 */
class BBoxCast implements CastsAttributes
{
    /**
     * @param  class-string<Box>  $boxClass
     */
    public function __construct(
        protected string $boxClass,
    ) {
    }

    /**
     * Cast the given value.
     *
     * @param  Model  $model
     */
    public function get($model, string $key, mixed $value, array $attributes)
    {
        if (! isset($value)) {
            return null;
        }

        if ($this->boxClass === Box::class) {
            if (Str::contains($value, 'BOX3D', true)) {
                return Box3D::fromString($value);
            }

            return Box2D::fromString($value);
        }

        return $this->boxClass::fromString($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @return string
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        return $value->toString();
    }
}
