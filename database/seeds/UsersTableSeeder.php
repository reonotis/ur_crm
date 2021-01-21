<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '藤澤怜臣',
                'email' => 'test@test.jp',
                'password' => Hash::make('reonotis'),
                'authority' => '1',
                'enrollment' => '1',
            ],[
                'name' => '営業さん１',
                'email' => 'test1@test.jp',
                'password' => Hash::make('reonotis'),
                'authority' => '1',
                'enrollment' => '1',
            ]
        ]);
    }
}
