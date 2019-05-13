<?php

use Faker\Generator as Faker;
use Gcr\Viability;

$factory->define(Viability::class, function (Faker $faker) {
    return [
        'property_type' => $faker->randomElement(Viability::attributeCodes('property_type')),
        'registration_number' => $faker->numberBetween(10000, 99999999),
        'property_area' => $faker->numerify('###,##'),
        'establishment_area' => $faker->numerify('###,##'),
        'same_as_business_address' => $faker->boolean,
        'thirst' => $faker->boolean,
        'administrative_office' => $faker->boolean,
        'closed_deposit' => $faker->boolean,
        'warehouse' => $faker->boolean,
        'repair_workshop' => $faker->boolean,
        'garage' => $faker->boolean,
        'fuel_supply_unit' => $faker->boolean,
        'exposure_point' => $faker->boolean,
        'training_center' => $faker->boolean,
        'data_processing_center' => $faker->boolean,
    ];
});
