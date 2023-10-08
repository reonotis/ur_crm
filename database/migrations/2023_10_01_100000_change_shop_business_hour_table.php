<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class ChangeShopBusinessHourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 削除
        Schema::table('shop_business_hour', function (Blueprint $table) {
            $table->dropColumn('business_hour_type');
            $table->dropColumn('week_no');
        });

        // 追加
        Schema::table('shop_business_hour', function (Blueprint $table) {
            $table->unsignedTinyInteger('week_no')->after('shop_id')->comment('曜日No');
            $table->unsignedTinyInteger('regular_holiday')->nullable()->after('week_no')->comment('定休日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 追加したものを戻す
        Schema::table('shop_business_hour', function (Blueprint $table) {
            $table->dropColumn('regular_holiday');
        });

        // 削除したものを戻す
        Schema::table('shop_business_hour', function (Blueprint $table) {
            $table->integer('business_hour_type')->nullable()->after('shop_id')->comment('1:毎日同時間として設定,2:曜日毎に設定');
        });
    }
}
