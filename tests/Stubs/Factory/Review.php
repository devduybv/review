<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Review\Entities\Review;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'resource_type' => 'posts',
        'resource_id' => 1,
        'review' => $faker->words(rand(4, 7), true),
        'rating' => 5,
        'images' => $faker->words(rand(1, 1), true),
        'status' => 1,
    ];
});
