<?php

use Illuminate\Database\Seeder;

class ReserveInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\ReserveInfo::class, 50)->create();
    }
}
