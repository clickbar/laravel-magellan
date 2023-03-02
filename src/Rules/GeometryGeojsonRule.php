<?php

namespace Clickbar\Magellan\Rules;

use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\App;

/** @phpstan-ignore-next-line We still need to support Laravel 9 */
class GeometryGeojsonRule implements InvokableRule
{
    private array $allowedGeometries;

    /**
     * @param  string[]  $allowedGeometries
     */
    public function __construct(array $allowedGeometries = [])
    {
        $this->allowedGeometries = $allowedGeometries;
    }

    public function __invoke($attribute, $value, $fail)
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
