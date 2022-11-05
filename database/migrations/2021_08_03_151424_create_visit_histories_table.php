<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_histories', function (Blueprint $table) {
            $table->bigIncrements('id')               ->comment('ID');
            $table->date('vis_date')                  ->comment('来店日');
            $table->time('vis_time')                  ->comment('来店時間');
            $table->integer('customer_id')            ->comment('来店顧客');
            $table->tinyInteger('shop_id')            ->comment('来店店舗');
            $table->tinyInteger('user_id')           ->comment('担当スタイリスト');
            $table->tinyInteger('menu_id')->default('0')->comment('メニュー');
            $table->tinyInteger('visit_type_id')->default('0')->comment('来店タイプ');
            $table->unsignedInteger('visit_reserve_id')->nullable()->comment('予約していた場合の予約ID');
            $table->unsignedTinyInteger('status')->default('1')->comment('ステータス 1:来店 9:キャンセル');
            $table->string('memo','10000') ->nullable()->comment('メモ');

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
        Schema::dropIfExists('visit_histories');
    }
}
