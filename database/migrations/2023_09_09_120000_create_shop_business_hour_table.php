<?php

use App\Consts\ShopSettingConst;
use App\Models\Shop;
use App\Models\ShopBusinessHour;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateShopBusinessHourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_business_hour', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shop_id')->comment('店舗_id');

            $table->integer('business_hour_type')->nullable()->comment('1:毎日同時間として設定,2:曜日毎に設定');
            $table->integer('week_no')->nullable()->comment('曜日毎に登録している場合の曜日No');

            $table->time('business_open_time')->comment('営業開始時間');
            $table->time('business_close_time')->comment('営業終了時間');
            $table->time('last_reception_time')->comment('最終受付時間');

            $table->date('setting_start_date')->nullable()->comment('適用開始日');
            $table->date('setting_end_date')->nullable()->comment('適用終了日');

            $table->date('created_by')->comment('作成者');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes();
        });

        // 既存店舗に対しデータを登録
        $shops = Shop::get();
        $records = [];
        foreach ($shops as $shop) {
            $records[] = [
                'shop_id' => $shop->id,
                'business_hour_type' => ShopSettingConst::BUSINESS_HOUR_EVERYDAY,
                'business_open_time' => $shop->start_time,
                'business_close_time' => $shop->end_time,
                'last_reception_time' => $shop->last_reception_time,
                'setting_start_date' => Carbon::parse('now'),
            ];
        }
        ShopBusinessHour::insert($records);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_business_hour');
    }
}
