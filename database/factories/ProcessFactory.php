<?php

use Faker\Generator as Faker;
use Gcr\Process;

$factory->define(Gcr\Process::class, function (Faker $faker) {
    return [
        'editing' => true,
        'status_id' => $faker->numberBetween(1, 2),
        'type_company' => $faker->randomElement(Process::attributeCodes('type_company')),
        'operation' => $faker->randomElement(Process::attributeCodes('operation')),
        'description' => $faker->text,
        'fields_editing' => $faker->randomElements(Process::attributeCodes('fields_editing'), 2),
    ];
});
