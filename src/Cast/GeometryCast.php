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
 * @implements CastsAttributes<Geometry,Geometry>
 */
class GeometryCast implements CastsAttributes
{
    protected WKBParser $wkbParser;

    protected BaseGenerator $sqlGenerator;

    public function __construct()
    {
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
        return isset($value) ? $this->wkbParser->parse($value) : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        if ($value instanceof Geometry) {
            return $this->sqlGenerator->generate($value);
        }

        return $value;
    }
}
