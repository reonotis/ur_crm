<?php

use Illuminate\Database\Seeder;

class UsersInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_info')->insert([
            [
                'id' => '1',
                'intr_No' => 'PRA21051300001',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1986',
                'birthdayMonth' => '10',
                'birthdayDay' => '25',
            ],[
                'id' => '2',
                'intr_No' => 'PRA21051300002',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => NULL,
                'birthdayMonth' => NULL,
                'birthdayDay' => NULL,
            ],[
                'id' => '3',
                'intr_No' => 'PRA21051300003',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => NULL,
                'birthdayMonth' => NULL,
                'birthdayDay' => NULL,
            ],[
                'id' => '4',
                'intr_No' => 'PRA21051300004',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1972',
                'birthdayMonth' => '08',
                'birthdayDay' => '24',
            ],[
                'id' => '5',
                'intr_No' => 'PRA21051300005',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => NULL,
                'birthdayMonth' => NULL,
                'birthdayDay' => NULL,
            ],[
                'id' => '6',
                'intr_No' => 'PRA21051300006',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1967',
                'birthdayMonth' => '12',
                'birthdayDay' => '24',
            ],[
                'id' => '7',
                'intr_No' => 'PRA21051300007',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1970',
                'birthdayMonth' => '02',
                'birthdayDay' => '09',
            ],[
                'id' => '8',
                'intr_No' => 'PRA21051300008',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1980',
                'birthdayMonth' => '09',
                'birthdayDay' => '30',
            ],[
                'id' => '9',
                'intr_No' => 'PRA21051300009',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1966',
                'birthdayMonth' => '09',
                'birthdayDay' => '14',
            ],[
                'id' => '10',
                'intr_No' => 'PRA210513000010',
                'img_path' => NULL,
                'tel' => NULL,
                'birthdayYear' => '1978',
                'birthdayMonth' => '12',
                'birthdayDay' => '13',
            ]
        ]);
    }
}

