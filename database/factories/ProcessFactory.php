<?php

use Faker\Generator as Faker;

$factory->define(Gcr\Process::class, function (Faker $faker) {
    return [
        'status' => false,
        'type_company' => $faker->randomElement(['businessman', 'society', 'eireli', 'other']),
        'type' => $faker->randomElement(['creating', 'updating']),
        'description' => $faker->text,
    ];
});
