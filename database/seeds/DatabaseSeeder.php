<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(IndustriesTableSeeder::class);
        $this->call(SexsTableSeeder::class);
        $this->call(MeansTableSeeder::class);
        $this->call(ResultsTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(AnglesTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(AuthoritysTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
    }
}