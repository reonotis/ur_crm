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
        $this->call(UsersTableSeeder::class);
        // $this->call(IndustriesTableSeeder::class);
        // $this->call(MeansTableSeeder::class);
        // $this->call(ResultsTableSeeder::class);
        // $this->call(AuthoritysTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(CustomerSchedulesTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(CoursePurchaseDetailsTableSeeder::class);
        $this->call(UsersInfoTableSeeder::class);
    }
}