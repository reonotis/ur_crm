<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseScheduleListTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_schedule_list_transactions', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('コーストランID');
            $table->string('course_title','200')           ->comment('コース名');
            $table->timestamp('date1')         ->nullable()->comment('日程1');
            $table->timestamp('date2')         ->nullable()->comment('日程2');
            $table->timestamp('date3')         ->nullable()->comment('日程3');
            $table->timestamp('date4')         ->nullable()->comment('日程4');
            $table->timestamp('date5')         ->nullable()->comment('日程5');
            $table->timestamp('date6')         ->nullable()->comment('日程6');
            $table->timestamp('date7')         ->nullable()->comment('日程7');
            $table->timestamp('date8')         ->nullable()->comment('日程8');
            $table->timestamp('date9')         ->nullable()->comment('日程9');
            $table->timestamp('date10')        ->nullable()->comment('日程10');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            // $table->boolean('delete_flag')->default('0')->comment('削除フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_schedule_list_transactions');
    }
}
