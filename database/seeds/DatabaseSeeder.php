<?php

use App\Models\Place;
use App\Models\PlaceComment;
use App\Models\PlaceField;
use App\Models\PlaceLike;
use App\Models\PlacePhoto;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\UnreachableUrl;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** @var Faker $faker */
        $faker = app(Faker::class);

        factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@app.com',
            'roles' => ['admin'],
        ]);

        factory(User::class)->create([
            'name' => 'user',
            'email' => 'user@app.com',
        ]);

        /** @var Collection $users */
        $users = factory(User::class, 50)->create();

        $nameGenerator = $faker->unique();
        /* @var Collection $places */
        $places = Collection::times(50, function () use ($nameGenerator) {
            $name = rtrim($nameGenerator->sentence(4), '.');
            return factory(Place::class)->create([
                'name' => $name,
                'slug' => \Str::slug($name),
            ]);
        });

        // create fields for places
        $places->each(function (Place $place) use ($faker) {
            factory(PlaceField::class, $faker->numberBetween(0, 6))->create([
                'place_id' => $place->id,
            ]);
        });

        // create comments for places
        $places->each(function (Place $place) use ($users, $faker) {
            $commentators = $users->random($faker->numberBetween(0, 10));

            /** @var User $commentator */
            foreach ($commentators as $commentator) {
                factory(PlaceComment::class, $faker->numberBetween(1, 2))->create([
                    'place_id' => $place->id,
                    'author_id' => $commentator->id,
                ]);
            }
        });

        // create likes for places
        $places->each(function (Place $place) use ($users, $faker) {
            $likingUsers = $users->random($faker->numberBetween(0, 30));
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
        $places->each(function (Place $place) use ($faker) {
            $count = $faker->numberBetween(0, 6);
            for ($i = 0; $i < $count; ++$i) {
                while (true) {
                    try {
                        $place
                            ->addMediaFromUrl('https://picsum.photos/600/900')
                            ->toMediaCollection('photos')
                        ;

                        break;
                    } catch (UnreachableUrl $exception) {
                        $this->command->warn(
                            'Failed to fetch random photo. Perhaps, bad internet connection. Retrying...'
                        );

                        sleep(1);
                    }
                }
            }
            $place->save();
        });
    }
}
