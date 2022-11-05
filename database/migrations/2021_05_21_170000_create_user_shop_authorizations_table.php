<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserShopAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shop_authorizations', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('shop_id')->unsigned()->comment('ショップID');
            $table->integer('user_id')->unsigned()->comment('ユーザーID');

            $table->boolean('user_read')->default('0')->comment('ユーザー閲覧');
            $table->boolean('user_create')->default('0')->comment('ユーザー作成');
            $table->boolean('user_edit')->default('0')->comment('ユーザー編集');
            $table->boolean('user_delete')->default('0')->comment('ユーザー削除');

            $table->boolean('customer_read')->default('0')->comment('顧客閲覧');
            $table->boolean('customer_read_none_mask')->default('0')->comment('顧客閲覧時のマスク処理');
            $table->boolean('customer_create')->default('0')->comment('顧客作成');
            $table->boolean('customer_edit')->default('0')->comment('顧客編集');
            $table->boolean('customer_delete')->default('0')->comment('顧客削除');

            $table->boolean('reserve_read')->default('0')->comment('予約閲覧');
            $table->boolean('reserve_create')->default('0')->comment('予約作成');
            $table->boolean('reserve_edit')->default('0')->comment('予約編集');
            $table->boolean('reserve_delete')->default('0')->comment('予約削除');

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
        Schema::dropIfExists('user_shop_authorizations');
    }
}
// php artisan make:model UserShop -a
