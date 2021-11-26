<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices_statuses', function (Blueprint $table) {
            $table->bigIncrements('id')               ->comment('ID');
            $table->tinyInteger('notice_id')          ->comment('お知らせID');
            $table->integer('user_id')                ->comment('ユーザーID');
            $table->tinyInteger('notice_status')->default('0')->comment('既読ステータス　0:未読 1:既読 9:削除');
            $table->timestamp('read_at')  ->nullable()->comment('ユーザーが既読にした日時');
            $table->integer('del_user_id')->nullable()->comment('削除したユーザー');
            $table->timestamp('del_at')   ->nullable()->comment('ユーザーが削除した日時');

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
        Schema::dropIfExists('notices_statuses');
    }
}
