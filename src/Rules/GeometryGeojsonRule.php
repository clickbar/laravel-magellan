<?php

namespace Clickbar\Magellan\Rules;

use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;
use Illuminate\Translation\PotentiallyTranslatedString;

class GeometryGeojsonRule implements ValidationRule
{
    private array $allowedGeometries;

    private int $srid = 4326;

    /**
     * @param  string[]  $allowedGeometries
     */
    public function __construct(array $allowedGeometries = [], int $srid = 4326)
    {
        $this->allowedGeometries = $allowedGeometries;
        $this->srid = $srid;
    }

    /**
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     *
     * @throws BindingResolutionException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var GeojsonParser $parser */
        $parser = App::make(GeojsonParser::class);

        try {
            $geometry = $parser->parseWithSrid($value, $this->srid);
            if (! empty($this->allowedGeometries) && ! in_array($geometry::class, $this->allowedGeometries)) {
                $class = $geometry::class;
                $fail("The geometry $class is not allowed");
            }
        } catch (\Exception $exception) {
            $fail($exception->getMessage());
        }
    }
}
