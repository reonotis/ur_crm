<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_reserves', function (Blueprint $table) {
            $table->bigIncrements('id')               ->comment('ID');
            $table->date('vis_date')                  ->comment('予約日');
            $table->time('vis_time')                  ->comment('予約時間');
            $table->integer('customer_id')            ->comment('顧客ID');
            $table->tinyInteger('shop_id')            ->comment('店舗ID');
            $table->tinyInteger('staff_id')           ->comment('担当スタイリスト');
            $table->tinyInteger('menu_id')->default('0')->comment('メニュー');
            $table->timestamp('reserve_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('予約を入れた日時')	;
            $table->string('memo','1000') ->nullable()->comment('メモ');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')->default('0')->comment('削除フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_reserves');
    }
}
