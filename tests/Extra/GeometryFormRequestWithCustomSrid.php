<?php

namespace Clickbar\Magellan\Tests\Extra;

use Clickbar\Magellan\Http\Requests\TransformsGeojsonGeometry;
use Clickbar\Magellan\Rules\GeometryGeojsonRule;
use Illuminate\Foundation\Http\FormRequest;

class GeometryFormRequestWithCustomSrid extends FormRequest
{
    use TransformsGeojsonGeometry;

    public function rules(): array
    {
        return [
            'point' => ['required', new GeometryGeojsonRule(srid: 25832)],
            'nullable_point' => ['nullable', new GeometryGeojsonRule(srid: 25832)],
        ];
    }

    public function geometries(): array
    {
        return ['point', 'nullable_point'];
    }

    public function geometrySrids(): array
    {
        return [
            'point' => 25832,
            'nullable_point' => 25832,
        ];
    }
}
