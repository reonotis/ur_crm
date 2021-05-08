<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims_transactions', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->integer('user_type')                   ->comment('1:顧客宛  2:インストラクター宛');
            $table->integer('user_id')                     ->comment('請求相手のID');
            $table->string('title','100')    ->nullable()  ->comment('項目');
            $table->date('limit_date')       ->nullable()  ->comment('期日');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag') ->default('0')  ->comment('削除フラグ');
        });
        DB::statement("ALTER TABLE payments COMMENT '請求情報トラン'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claims_transactions');
    }
}
