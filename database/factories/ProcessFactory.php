<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$template = implode('', array_map(function ($item) {
    return Arr::random([ '?', '#' ]);
}, range(0, 10)));

$factory->define(Gcr\Process::class, function (Faker $faker) use ($template) {
    $protocolPrefix = $faker->randomElement(['businessman', 'society', 'ireli', 'others']);
    return [
        'protocol' => $protocolPrefix .'-'.$faker->bothify($template),
    ];
});
