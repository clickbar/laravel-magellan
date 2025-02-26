<?php

namespace Clickbar\Magellan\Rules;

use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;

class GeometryGeojsonRule implements ValidationRule
{
    private array $allowedGeometries;

    /**
     * @param  string[]  $allowedGeometries
     */
    public function __construct(array $allowedGeometries = [])
    {
        $this->allowedGeometries = $allowedGeometries;
    }

    /**
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     *
     * @throws BindingResolutionException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var GeojsonParser $parser */
        $parser = App::make(GeojsonParser::class);

        try {
            $geometry = $parser->parse($value);
            if (! empty($this->allowedGeometries) && ! in_array($geometry::class, $this->allowedGeometries)) {
                $class = $geometry::class;
                $fail("The geometry $class is not allowed");
            }
        } catch (\Exception $exception) {
            $fail($exception->getMessage());
        }
    }
}
