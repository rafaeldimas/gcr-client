<?php

use Faker\Generator as Faker;

$factory->define(Gcr\Process::class, function (Faker $faker) {
    $type = $faker->randomElement(['businessman', 'society', 'ireli', 'others']);
    return [
        'protocol' => $type.'-'.date('Ymd'),
        'status' => false,
    ];
});
