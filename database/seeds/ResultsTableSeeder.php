<?php

use Illuminate\Database\Seeder;

class ResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('results')->insert([
            [
                'id' => '1',
                'result_name' => '不通',
            ],[
                'id' => '2',
                'result_name' => '受付ブロック',
            ],[
                'id' => '3',
                'result_name' => 'アポ獲得',
            ],[
                'id' => '4',
                'result_name' => '受注',
            ]
        ]);
        //
    }
}
