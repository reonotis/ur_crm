<?php

use Illuminate\Database\Seeder;
use App\Models\Course;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            [
                'course_name' => 'パラリンビクス講座',
                'parent_id' => NULL,
                'how_many_times' => '3',
                'price' => NULL
            ],[
                'course_name' => 'Ａ講座（ストレッチ系）',
                'parent_id' => '1',
                'how_many_times' => '5',
                'price' => '19800'
            ],[
                'course_name' => 'Ｂ講座（身体能力アップ系）',
                'parent_id' => '1',
                'how_many_times' => '5',
                'price' => '19800'
            ],[
                'course_name' => 'Ｃ講座（筋力・体力系）',
                'parent_id' => '1',
                'how_many_times' => '5',
                'price' => '19800'
            ],[
                'course_name' => 'インストラクター養成講座',
                'parent_id' => NULL,
                'how_many_times' => '1',
                'price' => NULL
            ],[
                'course_name' => 'パラリンビクスインストラクター養成講座',
                'parent_id' => '5',
                'how_many_times' => '5',
                'price' => '360000'
            ]
        ]);
        // factory(Course::class, 100)->create();
        //
    }
}
