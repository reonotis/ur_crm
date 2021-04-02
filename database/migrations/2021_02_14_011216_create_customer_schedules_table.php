<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_schedules', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('customer_id')->comment('顧客ID');
            $table->date('date')->nullable()->comment('受講日時');
            $table->time('time','0')->default('12:00:00')->comment('受講時間');
            $table->integer('course_schedules_id')->comment('コーススケジュールID');
            $table->tinyInteger('howMany')->nullable()->comment('何回目の受講か');
            $table->integer('instructor_id')->nullable()->comment('イントラID');

            $table->tinyInteger('status')->default('0')->comment('受講状態');
            $table->string('comment','1000')->nullable()->comment('コメント');
            $table->string('memo','1000')->nullable()->comment('メモ');

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
        Schema::dropIfExists('customer_schedules');
    }
}
