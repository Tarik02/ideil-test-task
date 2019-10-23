<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Place;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {
    $name = rtrim($faker->sentence(4), '.');
    $createdAt = $faker->dateTimeBetween('-30 days');
    $updatedAt = $faker->boolean(95)
        ? null
        : $faker->dateTimeBetween($createdAt)
    ;

    return [
        'slug' => Str::slug($name),
        'name' => $name,
        'description' => $faker->text(350),
        'mark' => $faker->numberBetween(1, 10),
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
    ];
});
