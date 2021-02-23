<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->bigIncrements('id')                ->comment('user.id');
            $table->string('intr_No')     ->nullable() ->comment('イントラ番号');
            $table->string('tel')         ->nullable() ->comment('電話番号');

            $table->string('img_path')    ->nullable() ->comment('画像');
            $table->string('birthdayYear')->nullable() ->comment('誕生日年');
            $table->string('birthdayMonth')->nullable()->comment('誕生日月');
            $table->string('birthdayDay') ->nullable() ->comment('誕生日日');

            $table->string('zip21','3')   ->nullable() ->comment('郵便番号1');
            $table->string('zip22','4')   ->nullable() ->comment('郵便番号2');
            $table->string('pref21')      ->nullable() ->comment('都道府県');
            $table->string('addr21')      ->nullable() ->comment('市区町村');
            $table->string('strt21')      ->nullable() ->comment('所在');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')->default('0')->comment('削除フラグ');

            // 外部キー制約
            $table->foreign('id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_info');
    }
}
