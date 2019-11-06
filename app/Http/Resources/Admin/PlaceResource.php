<?php

namespace App\Http\Resources\Admin;

use App\Models\Place;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\Models\Media;

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
            'comments' => PlaceCommentResource::collection($this->whenLoaded('comments')),
            'fields' => PlaceFieldResource::collection($this->whenLoaded('fields')),
            'photos' => $this->whenLoaded('media', function () {
                return $this->getMedia('photos')->map(function (Media $photo) {
                    return [
                        'id' => $photo->id,
                        'preview' => $photo->getUrl('preview'),
                        'original' => $photo->getUrl(),
                        'visible' => $photo->getCustomProperty('visible', true),
                    ];
                });
            }),
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
