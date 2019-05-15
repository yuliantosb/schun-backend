<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Item;
use App\Uom;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence($nbWords = 2),
        'item_code' => rand(10000, 99999),
        'description' => $faker->paragraph($nbSentences = 2),
        'uom_id' => Uom::get()->random(1)->first()->id

    ];
});
