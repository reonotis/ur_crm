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
                'customer_id' => '0',
                'name' => '藤澤怜臣',
                'read' => 'フジサワ レオン',
                'email' => 'fujisawa@reonotis.jp',
                'password' => Hash::make('reonotis'),
                'authority_id' => '1',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '0',
                'name' => '穐里 明美',
                'read' => 'アキサト アケミ',
                'email' => 'akisato@paralymbics.jp',
                'password' => Hash::make('akemi'),
                'authority_id' => '2',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '0',
                'name' => '西川 薫',
                'read' => 'ニシカワ カオリ',
                'email' => 'nishikawa@paralymbics.jp',
                'password' => Hash::make('kaori'),
                'authority_id' => '3',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '4',
                'name' => '中土 清奈',
                'read' => 'ナカツチ キヨナ',
                'email' => 'kiyona.nk@gmail.com',
                'password' => Hash::make('19720824'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '5',
                'name' => '百瀬 直人',
                'read' => 'モモセ ナオト',
                'email' => '117to212@gmail.com',
                'password' => Hash::make('test'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '6',
                'name' => '進藤 路子',
                'read' => 'シンドウ ミチコ',
                'email' => 'yuyuanne20@gmail.com',
                'password' => Hash::make('19671214'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '7',
                'name' => '猪原 麻紀子',
                'read' => 'イノハラ マキコ',
                'email' => 'maki.5272.1794@gmail.com',
                'password' => Hash::make('19700209'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '8',
                'name' => '五十嵐 宏美',
                'read' => 'イガラシ ヒロミ',
                'email' => 'taromi1010@gmail.com',
                'password' => Hash::make('19800930'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '9',
                'name' => '長谷川 英子',
                'read' => 'ハセガワ ヒデコ',
                'email' => 'hasebeat915@gmail.com',
                'password' => Hash::make('19660914'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ],[
                'customer_id' => '10',
                'name' => '比嘉 杏奈',
                'read' => 'ヒガ アンナ',
                'email' => 'higa_a_0528@yahoo.co.jp',
                'password' => Hash::make('19781213'),
                'authority_id' => '7',
                'enrolled_id' => '1',
            ]
        ]);
    }
}
