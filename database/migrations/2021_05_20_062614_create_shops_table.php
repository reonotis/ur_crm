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
            $table->string('shop_name')->comment('店舗名');
            $table->string('shop_symbol')->comment('ショップ記号');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('img_pass')->nullable()->comment('画像パス');
            $table->time('start_time')->comment('営業開始時間');
            $table->time('end_time')->comment('営業終了時間');
            $table->time('last_reception_time')->comment('最終受付時間');

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
        Schema::dropIfExists('shops');
    }
}
