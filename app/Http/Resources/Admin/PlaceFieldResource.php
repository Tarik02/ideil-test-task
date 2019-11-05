<?php

namespace App\Http\Resources\Admin;

use App\Models\PlaceField;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read PlaceField $resource
 */
class PlaceFieldResource extends JsonResource
{
    public function toArray($request)
    {
        $resource = $this->resource;

        return [
            'key' => $resource->key,
            'value' => $resource->value,
        ];
    }
}
