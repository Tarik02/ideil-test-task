<?php

namespace App\Services;

use App\Models\Place;
use App\Models\PlaceLike;
use Illuminate\Auth\AuthenticationException;

class PlaceLikesService
{
    public function getLikeState(Place $place, ?int $userId = null): ?string
    {
        if ($userId === null) {
            if (null === $userId = \Auth::id()) {
                return null;
            }
        }

        $like = PlaceLike::where([
            'user_id' => $userId,
            'place_id' => $place->id,
        ])->first();

        if ($like !== null) {
            return $like->isLike() ? 'like' : 'dislike';
        }

        return null;
    }

    public function toggleLike(Place $place, int $value = 1, ?int $userId = null): ?string
    {
        assert($value === -1 || $value === 1);

        if ($userId === null) {
            if (null === $userId = \Auth::id()) {
                throw new AuthenticationException('Unauthenticated.');
            }
        }

        $like = PlaceLike::where([
            'user_id' => $userId,
            'place_id' => $place->id,
        ])->first();

        $likesChange = 0;
        $dislikesChange = 0;

        if ($like === null) {
            $like = new PlaceLike();
            $like->user_id = $userId;
            $like->place_id = $place->id;
            $like->value = $value;
            $like->save();

            if ($value === 1) {
                $likesChange = 1;
            } else {
                $dislikesChange = 1;
            }
        } else if ($like->value === $value) {
            // remove like/dislike
            if ($value === 1) {
                // we just remove a like
                $likesChange = -1;
                $dislikesChange = 0;
            } else {
                // we just remove a dislike
                $likesChange = 0;
                $dislikesChange = -1;
            }
            $like->value = 0;
            PlaceLike::where([
                'user_id' => $userId,
                'place_id' => $place->id,
            ])->delete();
        } else {
            // either replace like with dislike or replace dislike with like
            if ($value === 1) {
                // we put a like, so we remove a dislike
                $likesChange = 1;
                $dislikesChange = -1;
            } else {
                // we put a dislike, so we remove a like
                $likesChange = -1;
                $dislikesChange = 1;
            }
            $like->value = $value;
            $like->update();
        }

        if ($likesChange !== 0) {
            $place->likes_count += $likesChange;
            $place->syncOriginalAttribute('likes_count');
            Place::where('id', $place->id)->increment('likes_count', $likesChange);
        }

        if ($dislikesChange !== 0) {
            $place->dislikes_count += $dislikesChange;
            $place->syncOriginalAttribute('dislikes_count');
            Place::where('id', $place->id)->increment('dislikes_count', $dislikesChange);
        }

        switch ($like->value) {
        case -1:
            return 'dislike';
        case 0:
            return null;
        case 1:
            return 'like';
        }
    }
}
