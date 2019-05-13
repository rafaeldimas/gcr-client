<?php

use Faker\Generator as Faker;

$factory->define(Gcr\Status::class, function (Faker $faker) {
    return [
        'label' => $faker->colorName,
        'color' => $faker->hexColor,
    ];
});
