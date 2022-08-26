<?php

namespace Clickbar\Magellan\Http\Requests;

use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Illuminate\Support\Facades\App;

trait TransformsGeometry
{
    protected function passedValidation()
    {
        $this->transformGeometries($this->geometries());
    }

    protected function transformGeometries(array $attributes)
    {
        /** @var GeojsonParser $parser */
        $parser = App::make(GeojsonParser::class);

        foreach ($attributes as $key) {
            if (! $this->request->has($key)) {
                continue;
            }

            $this->request->set(
                $key,
                $parser->parse($this->request->get($key))
            );
        }
    }

    abstract public function geometries(): array;
}
