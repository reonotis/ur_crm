<?php

use App\Models\Shop;
use Carbon\Carbon;
use App\Models\ShopBusinessHour;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddColumnUserShpAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_shop_authorizations', function (Blueprint $table) {
            $table->integer('shop_setting_read')->unsigned()->after('reserve_delete')->comment('ショップ設定閲覧');
            $table->integer('shop_setting_edit')->unsigned()->after('shop_setting_read')->comment('ショップ設定編集');
            $table->integer('shop_setting_delete')->unsigned()->after('shop_setting_edit')->comment('ショップ設定削除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_shop_authorizations', function (Blueprint $table) {
            $table->dropColumn('shop_setting_read');
            $table->dropColumn('shop_setting_edit');
            $table->dropColumn('shop_setting_delete');
        });
    }
}
