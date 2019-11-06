<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PlaceCommentResource;
use App\Models\Place;
use App\Models\PlaceComment;
use App\Scopes\VisibleScope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class PlaceCommentController extends Controller
{
    public function show(string $place)
    {
        $place = Place::with([
            'comments' => function (HasMany $builder) {
                $builder->withoutGlobalScope(VisibleScope::class);
            },
        ])
            ->where('slug', $place)
            ->firstOrFail()
        ;

        return PlaceCommentResource::collection($place->comments);
    }

    public function update(string $place, $id, Request $request)
    {
        $comment = $this->getComment($place, $id);
        $comment->visible = $request->get('visible');
        $comment->save();
    }

    public function destroy(string $place, $id)
    {
        $comment = $this->getComment($place, $id);
        $comment->delete();
    }

    protected function getComment(string $placeSlug, $commentId): PlaceComment
    {
        $place = Place::where('slug', $placeSlug)->firstOrFail();
        $comment = PlaceComment::withoutGlobalScope(VisibleScope::class)->findOrFail($commentId);

        if ($comment->place_id !== $place->id) {
            abort(401, 'Bad Request');
        }

        return $comment;
    }
}
