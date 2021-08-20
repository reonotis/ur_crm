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
            $table->bigIncrements('id')               ->comment('ID');
            $table->string('TOKKAI_shop','5')->nullable()->comment('ショップ記号');
            $table->integer('TOKKAI_no')->nullable()  ->comment('登録順序');
            $table->string('member_number','20')->nullable()->comment('会員番号　※TOKKAI_SHOP + TOKKAI_number');
            $table->string('f_name')                  ->comment('苗字');
            $table->string('f_read')                  ->comment('ミョウジ');
            $table->string('l_name')                  ->comment('名前');
            $table->string('l_read')                  ->comment('ナマエ');
            $table->tinyInteger('sex')  ->nullable()  ->comment('性別');
            $table->string('tel')       ->nullable()  ->comment('電話番号');
            $table->string('email')     ->nullable()  ->comment('メールアドレス');
            $table->string('birthday_year') ->nullable() ->comment('誕生日年');
            $table->string('birthday_month')->nullable() ->comment('誕生日月');
            $table->string('birthday_day')  ->nullable() ->comment('誕生日日');
            $table->tinyInteger('shop_id')  ->nullable() ->comment('所属店舗');
            $table->tinyInteger('staff_id') ->nullable() ->comment('担当スタイリスト');
            $table->string('zip21','3') ->nullable()  ->comment('郵便番号1');
            $table->string('zip22','4') ->nullable()  ->comment('郵便番号2');
            $table->string('pref21')    ->nullable()  ->comment('都道府県');
            $table->string('addr21')    ->nullable()  ->comment('市区町村');
            $table->string('strt21')    ->nullable()  ->comment('所在');

            $table->string('question1') ->nullable()  ->comment('アンケート1');
            $table->string('comment','1000')->nullable()->comment('お客様からのコメント');
            $table->string('memo','2000')->nullable() ->comment('メモ');

            $table->tinyInteger('register_flow')->default('0')->comment('登録フロー　0:管理者による登録 1:顧客来店時登録');

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
