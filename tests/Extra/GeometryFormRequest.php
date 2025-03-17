<?php

namespace Clickbar\Magellan\Tests\Extra;

use Clickbar\Magellan\Http\Requests\TransformsGeojsonGeometry;
use Clickbar\Magellan\Rules\GeometryGeojsonRule;
use Illuminate\Foundation\Http\FormRequest;

class GeometryFormRequest extends FormRequest
{
    use TransformsGeojsonGeometry;

    public function rules(): array
    {
        return [
            'point' => ['required', new GeometryGeojsonRule],
            'nullable_point' => ['nullable', new GeometryGeojsonRule],
        ];
    }

    public function geometries(): array
    {
        return ['point', 'nullable_point'];
    }
}
