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
        // $this->call(CustomersTableSeeder::class);
        $this->call(ShopsTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(VisitTypesTableSeeder::class);
        $this->call(MenusTableSeeder::class);
    }
}

// php artisan make:seeder QuestionsTableSeeder
// php artisan db:seed --class=MenusTableSeeder