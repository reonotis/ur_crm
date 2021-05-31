<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            [
                'menberNumber' => 'PRA21051300001',
                'name' => '藤澤 怜臣',
                'read' => 'フジサワ レオン',
                'email' => 'fujisawa@reonotis.jp',
                'birthdayYear' => '1986',
                'birthdayMonth' => '10',
                'birthdayDay' => '25',
                'instructor' => '1',
            ],[
                'menberNumber' => 'PRA21051300002',
                'name' => '穐里 明美',
                'read' => 'アキサト アケミ',
                'email' => 'akisato@paralymbics.jp',
                'birthdayYear' => NULL,
                'birthdayMonth' => NULL,
                'birthdayDay' => NULL,
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300003',
                'name' => '西川 薫',
                'read' => 'ニシカワ カオリ',
                'email' => 'nishikawa@paralymbics.jp',
                'birthdayYear' => NULL,
                'birthdayMonth' => NULL,
                'birthdayDay' => NULL,
                'instructor' => '3',
            ],[
                'menberNumber' => 'PRA21051300004',
                'name' => '中土 清奈',
                'read' => 'ナカツチ キヨナ',
                'email' => 'kiyona.nk@gmail.com',
                'birthdayYear' => '1972',
                'birthdayMonth' => '08',
                'birthdayDay' => '24',
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300005',
                'name' => '百瀬 直人',
                'read' => 'モモセ ナオト',
                'email' => '117to212@gmail.com',
                'birthdayYear' => NULL,
                'birthdayMonth' => NULL,
                'birthdayDay' => NULL,
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300006',
                'name' => '進藤 路子',
                'read' => 'シンドウ ミチコ',
                'email' => 'yuyuanne20@gmail.com',
                'birthdayYear' => '1967',
                'birthdayMonth' => '12',
                'birthdayDay' => '24',
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300007',
                'name' => '猪原 麻紀子',
                'read' => 'イノハラ マキコ',
                'email' => 'maki.5272.1794@gmail.com',
                'birthdayYear' => '1970',
                'birthdayMonth' => '02',
                'birthdayDay' => '09',
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300008',
                'name' => '五十嵐 宏美',
                'read' => 'イガラシ ヒロミ',
                'email' => 'taromi1010@gmail.com',
                'birthdayYear' => '1980',
                'birthdayMonth' => '09',
                'birthdayDay' => '30',
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300009',
                'name' => '長谷川 英子',
                'read' => 'ハセガワ ヒデコ',
                'email' => 'hasebeat915@gmail.com',
                'birthdayYear' => '1966',
                'birthdayMonth' => '09',
                'birthdayDay' => '14',
                'instructor' => '2',
            ],[
                'menberNumber' => 'PRA21051300010',
                'name' => '比嘉 杏奈',
                'read' => 'ヒガ アンナ',
                'email' => 'higa_a_0528@yahoo.co.jp',
                'birthdayYear' => '1978',
                'birthdayMonth' => '12',
                'birthdayDay' => '13',
                'instructor' => '2',
            ]
        ]);
    }
}

        // factory(Customer::class, 20)->create();