<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseScheduleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_schedule_lists', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('course_schedules.id');
            $table->string('course_title','200')           ->comment('コースタイトル');
            $table->date('date1')                          ->comment('日付1');
            $table->time('time1')                          ->comment('時間1');
            $table->date('date2')                          ->comment('日付2');
            $table->time('time2')                          ->comment('時間2');
            $table->date('date3')                          ->comment('日付3');
            $table->time('time3')                          ->comment('時間3');
            $table->date('date4')                          ->comment('日付4');
            $table->time('time4')                          ->comment('時間4');
            $table->date('date5')                          ->comment('日付5');
            $table->time('time5')                          ->comment('時間5');
            $table->date('date6')              ->nullable()->comment('日付6');
            $table->time('time6')              ->nullable()->comment('時間6');
            $table->date('date7')              ->nullable()->comment('日付7');
            $table->time('time7')              ->nullable()->comment('時間7');
            $table->date('date8')              ->nullable()->comment('日付8');
            $table->time('time8')              ->nullable()->comment('時間8');
            $table->date('date9')              ->nullable()->comment('日付9');
            $table->time('time9')              ->nullable()->comment('時間9');
            $table->date('date10')             ->nullable()->comment('日付10');
            $table->time('time10')             ->nullable()->comment('時間10');


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
        Schema::dropIfExists('course_schedule_lists');
    }
}
