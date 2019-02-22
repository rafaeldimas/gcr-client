<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Person;

$factory->define(Gcr\Owner::class, function (Faker $faker) {
    $faker->addProvider(new Person($faker));

    return [
        'name' => $faker->name,
        'marital_status' => $faker->randomElement(['Solteiro', 'Casado', 'Divorciado', 'Viúvo', 'Separado']),
        'rg' => $faker->rg,
        'rg_expedition' => $faker->date(),
        'cpf' => $faker->cpf,
    ];
});
