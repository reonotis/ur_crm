<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopBusinessHourTemporaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_business_hour_temporary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shop_id')->comment('店舗_id');
            $table->unsignedTinyInteger('holiday')->nullable()->comment('0:営業 1:定休');
            $table->date('target_date')->nullable()->comment('適用日');
            $table->time('business_open_time')->nullable()->comment('営業開始時間');
            $table->time('business_close_time')->nullable()->comment('営業終了時間');
            $table->time('last_reception_time')->nullable()->comment('最終受付時間');

            $table->date('created_by')->comment('作成者');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_business_hour_temporary');
    }
}
