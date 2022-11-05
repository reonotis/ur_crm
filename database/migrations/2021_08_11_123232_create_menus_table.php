<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id')               ->comment('ID');
            $table->string('menu_name','20')->nullable()->comment('メニュー名');
            $table->string('menu_read','20')->nullable()->comment('メニューメイ');
            $table->tinyInteger('rank')  ->nullable() ->comment('並び順');
            $table->unsignedInteger('price')->nullable()->comment('料金');
            $table->string('shortening')->nullable()->comment('省略記号');

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
        Schema::dropIfExists('menus');
    }
}
