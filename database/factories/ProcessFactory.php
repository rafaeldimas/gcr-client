<?php

use Faker\Generator as Faker;

$factory->define(Gcr\Process::class, function (Faker $faker) {
    $type = $faker->randomElement(['businessman', 'society', 'eireli', 'other']);
    return [
        'status' => false,
        'type' => $type,
    ];
});
