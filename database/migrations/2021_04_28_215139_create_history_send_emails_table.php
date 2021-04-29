<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorySendEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_send_emails', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->integer('customer_id')                 ->comment('顧客ID');
            $table->integer('user_id')                     ->comment('送信したインストラクターID');

            $table->string('title')                        ->comment('タイトル');
            $table->string('text')                         ->comment('メール本文');
            $table->timestamp('send_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('送信日時');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag') ->default('0')  ->comment('削除フラグ');
        });
        DB::statement("ALTER TABLE payments COMMENT 'メール送信内容の記録'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_send_emails');
    }
}
