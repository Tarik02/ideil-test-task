<?php

namespace App\Http\Resources\Admin;

use App\Models\Place;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Place
 * @property-read Place $resource
 */
class PlaceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'mark' => $this->mark,
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'default_photo' => PlacePhotoResource::make($this->whenLoaded('defaultPhoto')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'fields' => PlaceFieldResource::collection($this->whenLoaded('fields')),
            'photos' => PlacePhotoResource::collection($this->whenLoaded('photos')),
        ];
    }

    public function with($request) {
        return [
            'meta' => [
                'url_prefix' => route('place.show', ['slug' => '']) . '/',
            ],
        ];
    }
}
