<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseScheduleWhensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_schedule_whens', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->integer('course_schedules_id')         ->comment('コーススケジュールID');
            $table->integer('instructor_id')               ->comment('インストラクターID');
            $table->timestamp('date')                      ->comment('日程');
            $table->tinyInteger('howMany')     ->nullable()->comment('何回目の受講か');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')->default('0')->comment('削除フラグ');
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE course_schedule_whens COMMENT 'コーススケジュールの日程を管理するテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_schedule_whens');
    }
}
