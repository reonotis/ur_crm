<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CustomerSchedule;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(CustomerSchedule::class, function (Faker $faker) {
    return [
        'customer_id'   => $faker->numberBetween($min = 1, $max = 10),

        'date'          => $faker->date(),
        'time'          => $faker->time('H:i'),

        'course_id'     => $faker->numberBetween($min = 1, $max = 4),
        'howMany'       => $faker->numberBetween($min = 1, $max = 5),
        'instructor_id' => $faker->numberBetween($min = 1, $max = 2),

    ];
});
