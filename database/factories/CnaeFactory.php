<?php

use Faker\Generator as Faker;

$factory->define(Gcr\Cnae::class, function (Faker $faker) {
    return [
        'number' => $faker->numerify('####-#/##')
    ];
});
