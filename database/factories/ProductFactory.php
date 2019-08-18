<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id'   => 1,
        'name'          => $faker->unique()->word,
        'description'   => $faker->sentence(),
    ];
});
