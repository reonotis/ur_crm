<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'id'         => '1',
                'menu_name'  => 'カット',
                'rank'       => '1',
            ],[
                'id'         => '2',
                'menu_name'  => 'カット & カラー',
                'rank'       => '2',
            ],[
                'id'         => '3',
                'menu_name'  => 'カット & パーマ',
                'rank'       => '3',
            ],[
                'id'         => '4',
                'menu_name'  => 'カット & トリートメント',
                'rank'       => '4',
            ],[
                'id'         => '5',
                'menu_name'  => 'カット & ストレートパーマ',
                'rank'       => '5',
            ],[
                'id'         => '6',
                'menu_name'  => 'カット & 縮毛矯正',
                'rank'       => '6',
            ],[
                'id'         => '7',
                'menu_name'  => 'カット & 酸熱トリートメント',
                'rank'       => '7',
            ],[
                'id'         => '8',
                'menu_name'  => 'セット',
                'rank'       => '8',
            ],[
                'id'         => '9',
                'menu_name'  => 'メイク',
                'rank'       => '9',
            ],[
                'id'         => '10',
                'menu_name'  => '着付け',
                'rank'       => '10',
            ],[
                'id'         => '11',
                'menu_name'  => 'カラーのみ',
                'rank'       => '11',
            ],[
                'id'         => '12',
                'menu_name'  => 'トリートメントのみ',
                'rank'       => '12',
            ],[
                'id'         => '13',
                'menu_name'  => '縮毛矯正のみ',
                'rank'       => '13',
            ],[
                'id'         => '14',
                'menu_name'  => '酸熱トリートメント',
                'rank'       => '14',
            ]
        ]);
    }
}
