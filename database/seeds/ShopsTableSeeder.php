<?php

use Illuminate\Database\Seeder;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->insert([
            [
                'id'         => '1',
                'shop_name'  => 'UR CASTLE',
                'shop_read'  => 'ユーアールキャッスル',
                'rank'       => '1',
                'tokkai_shop'=> 'CA',
                'manager_id' => NULL,
                'hidden_flag'=> 0,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
                'delete_flag'=> 0,
            ],[
                'id'         => '2',
                'shop_name'  => 'UR DELTA',
                'shop_read'  => 'ユーアールデルタ',
                'rank'       => '2',
                'tokkai_shop'=> 'DE',
                'manager_id' => NULL,
                'hidden_flag'=> 0,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
                'delete_flag'=> 0,
            ],[
                'id'         => '3',
                'shop_name'  => 'UR Arche',
                'shop_read'  => 'ユーアールアルシュ',
                'rank'       => '3',
                'tokkai_shop'=> 'AR',
                'manager_id' => NULL,
                'hidden_flag'=> 0,
                'created_at' => '2021-07-01 08:00:00',
                'updated_at' => '2021-07-01 08:00:00',
                'delete_flag'=> 0,
            ]
        ]);
    }
}
