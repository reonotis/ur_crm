<?php

use App\Models\ReserveInfo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReserveInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserve_info', function (Blueprint $table) {
            $table->unsignedTinyInteger('reserve_type')->default('0')->comment('予約タイプ')->after('status');
            $table->time('vis_end_time')->nullable()->comment('来店終了時間')->after('vis_time');

            // コメント修正
            DB::statement('alter table reserve_info modify column status tinyint default 0 comment "ステータス"');
        });

        // statusカラムの定義を変更する為更新
        ReserveInfo::where('status', 1)->update(['status' => ReserveInfo::STATUS['GUIDED']]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserve_info', function (Blueprint $table) {
            $table->dropColumn('reserve_type');
            $table->dropColumn('vis_end_time');
        });
    }
}
