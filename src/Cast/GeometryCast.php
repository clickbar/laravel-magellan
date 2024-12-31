<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\Generator\BaseGenerator;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * @internal Use the specific Geometry (sub)class as caster in the model e.g. Point::class or Geometry::class
 *
 * @template T of Geometry
 *
 * @implements CastsAttributes<T,T>
 */
class GeometryCast implements CastsAttributes
{
    protected WKBParser $wkbParser;

    protected BaseGenerator $sqlGenerator;

    /** @param class-string<T> $geometryClass */
    public function __construct(
        protected string $geometryClass,
    ) {
        $this->wkbParser = App::make(WKBParser::class);

        $generatorClass = config('magellan.sql_generator', WKTGenerator::class);
        $this->sqlGenerator = new $generatorClass;

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

        $geometry = $this->wkbParser->parse($value);
        $this->assertGeometryType($geometry);

        return $geometry;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        if ($value instanceof Geometry) {
            $this->assertGeometryType($value);

            return $this->sqlGenerator->generate($value);
        }

        return $value;
    }

    protected function assertGeometryType(Geometry $geometry): void
    {
        if (! $geometry instanceof $this->geometryClass) {
            $actualClass = get_class($geometry);
            throw new \InvalidArgumentException("Geometry type must be an instance of $this->geometryClass, $actualClass given");
        }
    }
}
