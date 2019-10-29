<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceComment;
use App\Models\PlaceLike;
use App\Services\PlaceLikesService;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /** @var PlaceLikesService $placeLikesService */
    protected $placeLikesService;

    public function __construct(PlaceLikesService $placeLikesService) {
        $this->placeLikesService = $placeLikesService;
    }

    public function show(string $slug)
    {
        $place = Place::with([
            'fields',
            'comments' => function ($comment) {
                return $comment->where('visible', 1)->with('author');
            },
        ])->where('slug', $slug)->firstOrFail();

        $likeState = $this->placeLikesService->getLikeState($place);

        return view('place.show', compact('place', 'likeState'));
    }

    public function like(Request $request, string $slug, int $value)
    {
        if (!in_array($value, [-1, 1])) {
            return response('Bad Request', 400);
        }

        $place = Place::where('slug', $slug)->firstOrFail();
        $likeState = $this->placeLikesService->toggleLike($place, $value);

        if ($request->ajax()) {
            return response()->json([
                'likes_count' => $place->likes_count,
                'dislikes_count' => $place->dislikes_count,
                'like_state' => $likeState,
            ]);
        } else {
            return redirect()->route('place.show', ['slug' => $slug]);
        }
    }

    public function comments(string $slug)
    {
        $place = Place::where('slug', $slug)->firstOrFail();

        return response()->json([
            'data' => $place->comments,
        ]);
    }

    public function addComment(string $slug, Request $request)
    {
        $place = Place::where('slug', $slug)->firstOrFail();

        $comment = new PlaceComment($request->all());
        $comment->author_id = \Auth::id();

        $place->comments()->save($comment);
    }
}
