<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $scheduled_date = $faker->dateTimeBetween('-600day', '-1day');

    return [

        'client_id' => $faker->numberBetween($min = 1, $max = 30),
        'user' => $faker->numberBetween($min = 1, $max = 3),
        'date'=>$scheduled_date->format('Y-m-d'),
        'product' => $faker->numberBetween($min = 1, $max = 3),
        'fee' => $faker->numberBetween($min = 0, $max = 300000),
        'status' => $faker->numberBetween($min = 1, $max = 3),



        //
    ];
});
