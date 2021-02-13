<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Customer::class, function (Faker $faker) {
    
    $scheduled_date = $faker->dateTimeBetween('-300day', '-1day');

    $randomYMD =  $scheduled_date->format('ymd'). $faker->numberBetween($min = 100, $max = 999) ;




    return [
        'menberNumber' => 'PAR' . $randomYMD  ,
        'name' => $faker->name(),
        'read' => $faker->kananame(),
        'sex' => $faker->numberBetween($min = 1, $max = 3),
        'tel' => $faker->phoneNumber(),
        'email' => $faker->safeEmail(),
        'birthdayYear' => $faker->numberBetween($min = 1950, $max = 2000),
        'birthdayMonth' => $faker->numberBetween($min = 1, $max = 12),
        'birthdayDay' => $faker->numberBetween($min = 1, $max = 28),
        'instructor' => $faker->numberBetween($min = 1, $max = 3),
        'zip21' => $faker->postcode1,
        'zip22' => $faker->postcode2,
        'pref21' => $faker->prefecture(),
        'addr21' => $faker->ward(),
        'strt21' => $faker->streetAddress(),
        'memo' => $faker->realText(100),
        'hidden_flag' => $faker->numberBetween($min = 0, $max = 1)
        //
    ];
});

