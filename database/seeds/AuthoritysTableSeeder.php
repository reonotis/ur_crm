<?php

use Illuminate\Database\Seeder;

class AuthoritysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Authoritys')->insert([
            [
                'id' => '1',
                'authority_name' => '管理者',
            ],[
                'id' => '2',
                'authority_name' => 'チームリーダー',
            ],[
                'id' => '3',
                'authority_name' => '社員',
            ]
        ]);
    }
}
