<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    $scheduled_date = $faker->dateTimeBetween('-150day', '-1day');
    return [
        'client_id' => $faker->numberBetween($min = 1, $max = 20),
        'history_datetime'=>$scheduled_date->format('Y-m-d H:i:s'),
        'means_id' => $faker->numberBetween($min = 1, $max = 7),
        'result_id' => $faker->numberBetween($min = 1, $max = 3),
        'staff' => $faker->numberBetween($min = 1, $max = 3),
        'recipient_name' => $faker->lastName()." 様",
        'recipient_role' => $faker->randomElement(['代表','採用担当者','不明','']),
        'recipient_sex' => $faker->numberBetween($min = 1, $max = 2),
        'person_charge_name' => $faker->lastName()." 様",
        'person_charge_role' => $faker->randomElement(['代表','採用担当者','不明','']),
        'person_charge_sex' => $faker->numberBetween($min = 1, $max = 2),
        'history_detail' => $faker->realText(200)
    ];
});
