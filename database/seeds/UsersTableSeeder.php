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
                'id'         => '1',
                'name'       => '藤澤',
                'email'      => 'fujisawa@reonotis.jp',
                'email_verified_at'=> NULL,
                'password'   => Hash::make('reonotis'),
                'shop_id'    => 1,
                'authority_id' => 1,
                'remember_token'   => NULL,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '2',
                'name'       => '中村 聡',
                'email'      => 'fujisawa2@reonotis.jp',
                'email_verified_at'=> NULL,
                'password'   => Hash::make('reonotis'),
                'shop_id'    => 2,
                'authority_id' => 4,
                'remember_token'   => NULL,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '3',
                'name'       => '須田 啓介',
                'email'      => 'fujisawa3@reonotis.jp',
                'email_verified_at'=> NULL,
                'password'   => Hash::make('reonotis'),
                'shop_id'    => 3,
                'authority_id' => 7,
                'remember_token'   => NULL,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '4',
                'name'       => 'casle staff1',
                'email'      => 'fujisawa4@reonotis.jp',
                'email_verified_at'=> NULL,
                'password'   => Hash::make('reonotis'),
                'shop_id'    => 1,
                'authority_id' => 7,
                'remember_token'   => NULL,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ],[
                'id'         => '5',
                'name'       => 'casle staff2',
                'email'      => 'fujisawa5@reonotis.jp',
                'email_verified_at'=> NULL,
                'password'   => Hash::make('reonotis'),
                'shop_id'    => 1,
                'authority_id' => 7,
                'remember_token'   => NULL,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
            ]
        ]);
    }
}
