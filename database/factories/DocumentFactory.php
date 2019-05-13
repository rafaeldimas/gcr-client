<?php

use Faker\Generator as Faker;
use Gcr\Document;
use Illuminate\Support\Facades\File;

$factory->define(Document::class, function (Faker $faker) {
    $filepath = storage_path('app/documents');
    if (!File::exists($filepath)) {
        File::makeDirectory($filepath, 0775, true);
    }

    return [
        'type' => $faker->randomElement(Document::attributeCodes('type')),
        'file' => $faker->image($filepath, 640, 480, null, false),
    ];
});
