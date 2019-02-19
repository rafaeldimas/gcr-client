<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$template = implode('', array_map(function ($item) {
    return Arr::random([ '?', '#' ]);
}, range(0, 10)));

$factory->define(Gcr\Process::class, function (Faker $faker) use ($template) {
    return [
        'protocol' => Arr::random(['businessman', 'society', 'ireli', 'others']).'-'.$faker->bothify($template),
    ];
});
