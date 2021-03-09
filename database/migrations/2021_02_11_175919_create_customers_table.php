<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('menberNumber','20')->nullable()->comment('会員番号');
            $table->string('name')->comment('名前');
            $table->string('read')->comment('ヨミ');
            $table->tinyInteger('sex')->nullable()->comment('性別');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('email')->nullable()->comment('メールアドレス');

            $table->string('birthdayYear')->nullable()->comment('誕生日年');
            $table->string('birthdayMonth')->nullable()->comment('誕生日月');
            $table->string('birthdayDay')->nullable()->comment('誕生日日');

            $table->tinyInteger('instructor')->comment('担当インストラクター');
            $table->string('zip21','3')->nullable()->comment('郵便番号1');
            $table->string('zip22','4')->nullable()->comment('郵便番号2');
            $table->string('pref21')->nullable()->comment('都道府県');
            $table->string('addr21')->nullable()->comment('市区町村');
            $table->string('strt21')->nullable()->comment('所在');
            $table->string('memo','500')->nullable()->comment('メモ');
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
        Schema::dropIfExists('customers');
    }
}
