<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            [
                'id'         => '1',
                'questions_no' => '1',
                'rank'       => '1',
                'answer_name'=> 'パサつき',
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '2',
                'questions_no' => '1',
                'rank'       => '2',
                'answer_name'=> 'コシ・ハリ',
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '3',
                'questions_no' => '1',
                'rank'       => '3',
                'answer_name'=> '枝毛',
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '4',
                'questions_no' => '1',
                'rank'       => '4',
                'answer_name'=> '白髪',
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '5',
                'questions_no' => '1',
                'rank'       => '5',
                'answer_name'=> 'ボリューム',
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '6',
                'questions_no' => '1',
                'rank'       => '6',
                'answer_name'=> '薄毛',
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ]
        ]);
    }
}

