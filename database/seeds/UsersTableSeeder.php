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
                'enrolled' => '1',
            ],[
                'name' => '穐里 明美',
                'email' => 'test1@test.jp',
                'password' => Hash::make('reonotis'),
                'authority' => '1',
                'enrolled' => '1',
            ],[
                'name' => 'インストラクターAさん',
                'email' => 'test2@test.jp',
                'password' => Hash::make('reonotis'),
                'authority' => '3',
                'enrolled' => '5',
            ],[
                'name' => 'インストラクターBさん',
                'email' => 'test3@test.jp',
                'password' => Hash::make('reonotis'),
                'authority' => '3',
                'enrolled' => '9',
            ]
        ]);
    }
}
