<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|min:5|max:128',
            'slug' => [
                'required',
                'alpha_dash',
                'max:128',
                Rule::unique('places')->ignore($this->id),
            ],
            'description' => 'required|max:20480',
            'mark' => 'integer|between:1,10',
            'fields.*.key' => 'required|max:64',
            'fields.*.value' => 'required|max:64',
            'photos.*.id' => 'nullable|integer',
            'photos.*.file' => 'nullable|integer',
            'photos.*.visible' => 'bool',
        ];
    }
}
