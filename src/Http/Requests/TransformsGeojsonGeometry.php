<?php

namespace Clickbar\Magellan\Http\Requests;

use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

trait TransformsGeojsonGeometry
{
    protected function passedValidation()
    {
        $this->transformGeometries($this->geometries());
    }

    protected function transformGeometries(array $attributes)
    {
        /** @var GeojsonParser $parser */
        $parser = App::make(GeojsonParser::class);
        $input = $this->all();
        foreach ($attributes as $key) {
            if (! isset($this[$key])) {
                continue;
            }
            Arr::set($input, $key, $parser->parse($this[$key]));
        }
        $this->replace($input);
    }

    abstract public function geometries(): array;
}
