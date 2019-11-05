<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
//            'author' => new UserResource($this->author),
            'text' => $this->text,
            'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
