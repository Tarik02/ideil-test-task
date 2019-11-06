<?php

namespace App\Http\Resources\Admin;

use App\Models\PlaceComment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PlaceComment
 */
class PlaceCommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author' => $this->whenLoaded('author', function () {
                return UserResource::make($this->author);
            }),
            'text' => $this->text,
            'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
