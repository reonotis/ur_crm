<?php

use Illuminate\Database\Seeder;

class SexsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sexs')->insert([
            [
                'id' => '1',
                'sex_name' => '男性',
            ],[
                'id' => '2',
                'sex_name' => '女性',
            ],[
                'id' => '3',
                'sex_name' => 'その他',
            ]
        ]);
        //
    }
}