<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Uom;
use Faker\Generator as Faker;

$factory->define(Uom::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
    ];
});
