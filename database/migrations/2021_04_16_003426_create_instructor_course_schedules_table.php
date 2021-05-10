<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorCourseSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_course_schedules', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->integer('instructor_courses_id')       ->comment('インストラクターコースID');
            $table->integer('instructor_id')               ->comment('インストラクターID');
            $table->timestamp('date')                      ->comment('日程');
            $table->tinyInteger('howMany')     ->nullable()->comment('何回目の受講か');

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
        Schema::dropIfExists('instructor_course_schedules');
    }
}
