<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id')                  ->comment('ID');
            $table->integer('customer_id')               ->comment('顧客ID');
            $table->date('date')                         ->comment('購入日');
            $table->integer('purchase_ID')               ->comment('購入したコースID');
            $table->integer('price')                     ->comment('料金');
            $table->integer('pay_confirm')->default('0') ->comment('入金確認');
            $table->date('payment_day')   ->nullable()   ->comment('入金日');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')->default('0')->comment('削除フラグ');
            // $ php artisan make:seeder CoursePurchaseDetailsTableSeeder
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_purchase_details');
    }
}
