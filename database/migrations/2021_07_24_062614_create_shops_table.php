<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {

            $table->bigIncrements('id')               ->comment('ID');
            $table->string('shop_name')               ->comment('店舗名');
            $table->string('shop_read')               ->comment('テンポメイ');
            $table->tinyInteger('rank')               ->comment('並び順');
            $table->string('tokkai_shop')             ->comment('特会ナンバー');
            $table->tinyInteger('manager_id')->nullable()->comment('店長※user__id');
            $table->boolean('hidden_flag')->default('0')->comment('非表示フラグ');

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
        Schema::dropIfExists('shops');
    }
}
