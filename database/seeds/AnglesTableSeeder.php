<?php

use Illuminate\Database\Seeder;

class AnglesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('angles')->insert([
            [
                'id' => '1',
                'angle_name' => 'A',
            ],[
                'id' => '2',
                'angle_name' => 'B',
            ],[
                'id' => '3',
                'angle_name' => 'C',
            ]
        ]);
    }
}
