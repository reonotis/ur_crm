<?php

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        factory(Client::class, 100)->create();
        // DB::table('clients')->insert([
        //     [
        //         'name' => '株式会社テスト',
        //         'read' => 'カブシキガイシャテスト',
        //     ],[
        //         'name' => '株式会社テスト',
        //         'read' => 'カブシキガイシャテスト',
        //     ],[
        //         'name' => '株式会社あいうえお',
        //         'read' => 'カブシキガイシャアイウエオ',
        //     ]
        // ]);
    }
}