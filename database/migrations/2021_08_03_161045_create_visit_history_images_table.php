<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitHistoryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_history_images', function (Blueprint $table) {
            $table->bigIncrements('id')               ->comment('ID');
            $table->integer('customer_id')            ->comment('顧客ID');
            $table->integer('visit_history_id')       ->comment('来店履歴ID');
            $table->tinyInteger('angle')              ->comment('角度 1:正面 2:横 3:背面 ');
            $table->string('img_pass')                ->comment('画像パス');

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
        Schema::dropIfExists('visit_history_images');
    }
}
