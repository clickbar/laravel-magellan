<?php

namespace Clickbar\Magellan\Http\Requests;

use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ValidatedInput;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
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
            if (! isset($this[$key]) || empty($this[$key])) {
                continue;
            }

            Arr::set($input, $key, $parser->parse($this[$key]));
        }

        // Note: This only replaces the values on the request itself, not
        // the data contained in ->validated() or ->safe(), we will override
        // those below.
        $this->replace($input);
    }

    abstract public function geometries(): array;

    /**
     * Get a validated input container for the validated input.
     *
     * @return \Illuminate\Support\ValidatedInput|array
     */
    public function safe(?array $keys = null)
    {
        return is_array($keys)
            ? (new ValidatedInput($this->validated()))->only($keys)
            : new ValidatedInput($this->validated());
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validatedData = $this->validator->validated();

        foreach ($this->geometries() as $geometryKey) {
            if (! isset($validatedData[$geometryKey]) || ! isset($this[$geometryKey])) {
                continue;
            }

            Arr::set($validatedData, $geometryKey, $this[$geometryKey]);
        }

        return data_get($validatedData, $key, $default);
    }
}
