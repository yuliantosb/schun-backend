<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\StockIn;
use App\Item;
use Faker\Generator as Faker;

$factory->define(StockIn::class, function (Faker $faker) {
    return [
        'item_id' => Item::get()->random(1)->first()->id,
        'stock_in_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Asia/Jakarta'),
        'qty' => $faker->numberBetween(100, 1000),
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 500000, $max = 100000000),
        'evidence' => str_replace(' ', '_', $faker->sentence(1).'jpg')
    ];
});
