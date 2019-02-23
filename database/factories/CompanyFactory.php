<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Company;

$factory->define(Gcr\Company::class, function (Faker $faker) {
    $faker->addProvider(new Company($faker));

    return [
        'name' => $faker->company,
        'share_capital' => $faker->randomFloat(2, 1, 999999),
        'activity_description' => $faker->sentence,
        'size' => $faker->randomElement(['Microempresa', 'Pequeno', 'Médio', 'Grande']),
        'signed' => $faker->date(),
    ];
});
