<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Company;
use Gcr\Company as CompanyModel;

$factory->define(CompanyModel::class, function (Faker $faker) {
    $faker->addProvider(new Company($faker));

    return [
        'name' => $faker->company,
        'share_capital' => $faker->randomFloat(2, 1, 999999),
        'activity_description' => $faker->sentence,
        'size' => $faker->randomElement(CompanyModel::attributeCodes('size')),
        'signed' => $faker->date(),
    ];
});
