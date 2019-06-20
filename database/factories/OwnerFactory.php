<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Person;
use Gcr\Owner;

$factory->define(Owner::class, function (Faker $faker) {
    $faker->addProvider(new Person($faker));

    $jobRoles = $faker->randomElements(Owner::attributeCodes('job_roles'), mt_rand(1, 4));
    $jobRolesOther = in_array(4, $jobRoles) ? $faker->word : '';

    return [
        'name' => $faker->name,
        'job_roles' => $jobRoles,
        'job_roles_other' => $jobRolesOther,
        'marital_status' => $faker->randomElement(Owner::attributeCodes('marital_status')),
        'wedding_regime' => $faker->randomElement(Owner::attributeCodes('wedding_regime')),
        'rg' => $faker->rg,
        'rg_expedition' => $faker->date(),
        'date_of_birth' => $faker->date(),
        'cpf' => $faker->cpf,
    ];
});
