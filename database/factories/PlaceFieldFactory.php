<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PlaceField;
use Faker\Generator as Faker;

$factory->define(PlaceField::class, function (Faker $faker) {
    return [
        'key' => $faker->words(mt_rand(1, 3), true),
        'value' => $faker->text(25),
    ];
});
