<?php

use Illuminate\Database\Seeder;
use App\Models\CoursePurchaseDetails;

class CoursePurchaseDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_purchase_details')->insert([
            [
                'customer_id' => '1',
                'date' => '2020-12-22',
                'purchase_id' => '6',
                'price' => '360000',
                'pay_confirm' => '0'
            ],[
                'customer_id' => '2',
                'date' => '2020-12-25',
                'purchase_id' => '6',
                'price' => '360000',
                'pay_confirm' => '0'
            ]
        ]);
        // factory(Course::class, 100)->create();
        //
    }
}
