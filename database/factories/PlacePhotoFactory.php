<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PlacePhoto;
use Faker\Generator as Faker;

$factory->define(PlacePhoto::class, function (Faker $faker) {
    $createdAt = $faker->dateTimeBetween('-30 days');
    $updatedAt = $faker->boolean(95)
        ? null
        : $faker->dateTimeBetween($createdAt)
    ;

    return [
        'visible' => $faker->boolean(95),
        'preview' => '', // will be set later
        'original' => '', // will be set later
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
    ];
});
