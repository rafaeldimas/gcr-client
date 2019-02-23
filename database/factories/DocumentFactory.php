<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;

static $typeNumber = 0;
$types = ['rg', 'cpf', 'iptu'];

$factory->define(Gcr\Document::class, function (Faker $faker) use ($typeNumber, $types) {
    $filepath = storage_path('app/public/images/documents');
    if (!File::exists($filepath)) {
        File::makeDirectory($filepath, 0775, true);
    }

    if ($typeNumber === 2) $typeNumber = 0;

    return [
        'type' => $types[$typeNumber],
        'file' => $faker->image($filepath,640,480, null, false),
    ];
});
