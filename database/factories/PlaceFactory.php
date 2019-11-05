<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Place;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {
    $createdAt = $faker->dateTimeBetween('-30 days');
    $updatedAt = $faker->boolean(95)
        ? null
        : $faker->dateTimeBetween($createdAt)
    ;

    return [
        // slug and name are filled outside of factory
        'description' => $faker->text(350),
        'mark' => $faker->numberBetween(1, 10),
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
    ];
});
