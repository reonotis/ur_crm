<?php

use Illuminate\Database\Seeder;

class MeansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('means')->insert([
            [
                'id' => '1',
                'mean_name' => 'メール送信',
            ],[
                'id' => '2',
                'mean_name' => 'メール受信',
            ],[
                'id' => '3',
                'mean_name' => '電話(架電)',
            ],[
                'id' => '4',
                'mean_name' => '電話(受電)',
            ],[
                'id' => '5',
                'mean_name' => '初訪',
            ],[
                'id' => '6',
                'mean_name' => '訪問',
            ],[
                'id' => '7',
                'mean_name' => '来社',
            ]
        ]);
    }
}