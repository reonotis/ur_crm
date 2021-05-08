<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->date('claim_date')                     ->comment('請求した年月日');
            $table->integer('user_type')                   ->comment('1:顧客宛  2:インストラクター宛');
            $table->integer('user_id')                     ->comment('請求相手のID');
            $table->string('title','100')    ->nullable()  ->comment('項目');
            $table->integer('price')         ->default('0')->comment('金額');
            $table->date('limit_date')       ->nullable()  ->comment('期日');
            $table->tinyInteger('status')    ->default('0')->comment('ステータス 0:未請求 1:請求中 3:cancel 5:支払い済み');
            $table->date('complete_date')    ->nullable()  ->comment('支払い日(計上日)');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag') ->default('0')  ->comment('削除フラグ');
        });
        DB::statement("ALTER TABLE payments COMMENT '請求情報'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claims');
    }
}
