<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Address;

$factory->define(Gcr\Address::class, function (Faker $faker) {
    $faker->addProvider(new Address($faker));

    return [
        'postcode' => $faker->postcode,
        'street' => $faker->streetAddress,
        'number' => $faker->buildingNumber,
        'complement' => $faker->secondaryAddress,
        'district' => $faker->word,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'country' => $faker->countryCode,
    ];
});
