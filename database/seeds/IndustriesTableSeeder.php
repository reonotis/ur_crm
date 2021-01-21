<?php

use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('industries')->insert([
            [
                'id' => '1',
                'name' => '飲食',
            ],[
                'id' => '2',
                'name' => 'IT',
            ],[
                'id' => '3',
                'name' => '営業',
            ]
        ]);
    }
}
