<?php

namespace App\Http\Resources\Admin;

use App\Models\PlacePhoto;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read PlacePhoto $resource
 */
class PlacePhotoResource extends JsonResource
{
    public function toArray($request)
    {
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'preview' => $resource->preview,
            'original' => $resource->original,
            'visible' => $resource->visible,
        ];
    }
}
