<?php

use App\Models\Place;
use App\Models\PlaceComment;
use App\Models\PlaceField;
use App\Models\PlaceLike;
use App\Models\PlacePhoto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@app.com',
        ]);

        /** @var Collection $users */
        $users = factory(User::class, 50)->create();

        /* @var Collection $places */
        $places = factory(Place::class, 50)->create();

        // create fields for places
        $places->each(function (Place $place) {
            factory(PlaceField::class, mt_rand(0, 6))->create([
                'place_id' => $place->id,
            ]);
        });

        // create comments for places
        $places->each(function (Place $place) use ($users) {
            $commentators = $users->random(mt_rand(0, 10));

            /** @var User $commentator */
            foreach ($commentators as $commentator) {
                factory(PlaceComment::class, mt_rand(1, 2))->create([
                    'place_id' => $place->id,
                    'author_id' => $commentator->id,
                ]);
            }
        });

        // create likes for places
        $places->each(function (Place $place) use ($users) {
            $likingUsers = $users->random(mt_rand(0, 30));
            $likesCount = 0;
            $dislikesCount = 0;

            /** @var User $user */
            foreach ($likingUsers as $user) {
                /** @var PlaceLike $like */
                $like = factory(PlaceLike::class)->create([
                    'user_id' => $user->id,
                    'place_id' => $place->id,
                ]);

                if ($like->value > 0) {
                    $likesCount += $like->value;
                } else {
                    $dislikesCount += -$like->value;
                }
            }

            $place->likes_count = $likesCount;
            $place->dislikes_count = $dislikesCount;
            $place->save();
        });

        // create photos for places
        $places->each(function (Place $place) {
            /** @var Collection $photos */
            $photos = Collection::times(mt_rand(1, 6))->map(function (int $i) use ($place) {
                /** @var PlacePhoto $photo */
                $photo = factory(PlacePhoto::class)->create([
                    'place_id' => $place->id,
                    'weight' => $i,
                ]);
                $imageFile = tempnam(sys_get_temp_dir(), 'laravel-photo');
                file_put_contents($imageFile, file_get_contents('https://picsum.photos/200/300'));
                $uploadedFile = new \Illuminate\Http\UploadedFile($imageFile, $imageFile);
                $photo->uploadImage($uploadedFile, 'preview');
                $photo->uploadImage($uploadedFile, 'original');
                return $photo;
            });
            $place->default_photo_id = $photos->random()->id;
            $place->save();
        });
    }
}
