<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Person;
use Gcr\Owner;

$factory->define(Owner::class, function (Faker $faker) {
    $faker->addProvider(new Person($faker));

    return [
        'name' => $faker->name,
        'job_role' => $faker->randomElement(Owner::attributeCodes('job_role')),
        'marital_status' => $faker->randomElement(Owner::attributeCodes('marital_status')),
        'wedding_regime' => $faker->randomElement(Owner::attributeCodes('wedding_regime')),
        'rg' => $faker->rg,
        'rg_expedition' => $faker->date(),
        'date_of_birth' => $faker->date(),
        'cpf' => $faker->cpf,
    ];
});
