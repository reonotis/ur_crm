<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name')->comment('名前');
            $table->string('read')->comment('ヨミ');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('fax')->nullable()->comment('fax');
            $table->tinyInteger('status')->nullable()->comment('状態');
            $table->tinyInteger('angle')->nullable()->comment('角度');
            $table->tinyInteger('relationship')->nullable()->comment('関係性');
            $table->timestamp('recall')->nullable()->comment('再コール日');
            $table->unsignedTinyInteger('industry_id')->nullable()->comment('業種');
            $table->tinyInteger('user')->nullable()->comment('担当営業');
            $table->string('zip21','3')->nullable()->comment('郵便番号1');
            $table->string('zip22','4')->nullable()->comment('郵便番号2');
            $table->string('pref21')->nullable()->comment('都道府県');
            $table->string('addr21')->nullable()->comment('市区町村');
            $table->string('strt21')->nullable()->comment('所在');
            $table->integer('capital')->nullable()->comment('資本金');
            $table->string('memo','500')->nullable()->comment('メモ');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');

            // 外部キー制約
            $table->foreign('industry_id')->references('id')->on('industries');

            // establishment_day     // 創業日
            // client_salse_staff    // 営業担当
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
