<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PlaceLike;
use Faker\Generator as Faker;

$factory->define(PlaceLike::class, function (Faker $faker) {
    return [
        'value' => $faker->boolean(90) ? 1 : -1,
    ];
});
