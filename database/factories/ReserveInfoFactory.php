<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ReserveInfo;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(ReserveInfo::class, function (Faker $faker) {

    // 開始時間と終了時間
    $vis_time = Carbon::createFromTime($faker->numberBetween(11, 18), $faker->randomElement(['00', '15', '30', '45']));
    $vis_end_time = $vis_time->copy();
    $vis_end_time->addMinutes($faker->randomElement(['60', '75', '90', '105']));

    return [
        'vis_date' => $faker->dateTimeBetween('-1 day', '+3 day')->format('Y-m-d'),
        'vis_time' => $vis_time,
        'vis_end_time' => $vis_end_time,
        'customer_id' => $faker->numberBetween(1, 1000),
        'shop_id' => 1,
        'user_id' => $faker->numberBetween(1, 10),
        'menu_id' => 0,
        'visit_type_id' => 0,
        'visit_reserve_id' => NULL,
        'status' => $faker->randomElement([0, 1, 5]),
        'reserve_type' => $faker->randomElement([0, 1, 5]),
        'memo' => '',
    ];
});

