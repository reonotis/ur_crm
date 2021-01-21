<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Client::class, function (Faker $faker) {
    $scheduled_date = $faker->dateTimeBetween('+1day', '+10day');
    return [
        'name' => $faker->company(),
        'read' => 'テスト',
        'tel' => $faker->phoneNumber(),
        'fax' => $faker->phoneNumber(),
        'status' => $faker->numberBetween($min = 1, $max = 3),
        'angle' => $faker->numberBetween($min = 1, $max = 3),
        'relationship' => $faker->numberBetween($min = 1, $max = 3),
        'recall'=>$scheduled_date->format('Y-m-d H:i:s'),
        'industry_id' => $faker->numberBetween($min = 1, $max = 3),
        'zip21' => $faker->postcode1,
        'zip22' => $faker->postcode2,
        'pref21' => $faker->prefecture(),
        'addr21' => $faker->ward(),
        'strt21' => $faker->streetAddress(),
        'memo' => $faker->realText(100)
        //
    ];
});

