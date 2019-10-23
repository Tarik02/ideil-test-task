<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PlaceComment;
use Faker\Generator as Faker;

$factory->define(PlaceComment::class, function (Faker $faker) {
    $createdAt = $faker->dateTimeBetween('-30 days');
    $updatedAt = $faker->boolean(95)
        ? null
        : $faker->dateTimeBetween($createdAt)
        ;

    return [
        'text' => $faker->text(80),
        'visible' => $faker->boolean(80),
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
    ];
});
