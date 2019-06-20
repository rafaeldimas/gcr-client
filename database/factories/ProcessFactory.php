<?php

use Faker\Generator as Faker;
use Gcr\Process;

$factory->define(Gcr\Process::class, function (Faker $faker) {
    $operations = Process::attributeCodes('operation');
    $typesCompanies = Process::attributeCodes('type_company');

    $operation = $faker->randomElement($operations);
    $newTypeCompany = ($operation === Process::OPERATION_TRANSFORMATION) ? $faker->randomElement($typesCompanies) : null;

    return [
        'editing' => true,
        'type_company' => $faker->randomElement($typesCompanies),
        'new_type_company' => $newTypeCompany,
        'operation' => $operation,
        'description' => $faker->text,
        'fields_editing' => $faker->randomElements(Process::attributeCodes('fields_editing'), 2),
    ];
});
