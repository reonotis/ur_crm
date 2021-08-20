<?php

use Illuminate\Database\Seeder;

class VisitTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('visit_types')->insert([
            [
                'id'         => '1',
                'type_name'       => 'S_指名',
            ],[
                'id'         => '2',
                'type_name'       => 'SH_紹介',
            ],[
                'id'         => '3',
                'type_name'       => 'K_交代',
            ],[
                'id'         => '4',
                'type_name'       => 'F_フリー',
            ],[
                'id'         => '5',
                'type_name'       => 'D_代理',
            ]
        ]);
    }
}
