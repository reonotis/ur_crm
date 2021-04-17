<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_courses', function (Blueprint $table) {
            $table->bigIncrements('id')                   ->comment('ID');
            $table->date('date')              ->nullable()->comment('受講日時');
            $table->time('time')              ->nullable()->comment('開催時間');
            $table->integer('instructor_id')              ->comment('イントラID');
            $table->tinyInteger('course_id')              ->comment('コースID');
            $table->text('course_title','100')->nullable()->comment('コースタイトル');
            $table->text('erea')              ->nullable()->comment('エリア');
            $table->text('venue')             ->nullable()->comment('会場');
            $table->integer('price')          ->nullable()->comment('金額');
            $table->string('notices','1000')  ->nullable()->comment('特記事項');
            $table->string('comment','1000')  ->nullable()->comment('詳細');
            
            $table->integer('approval_flg') ->default('2')->comment('承認状態 0:未承認 1:差し戻し 2:申請中 5:承認済み');
            
            $table->timestamp('open_start_day')->nullable()->comment('公開開始日');
            $table->timestamp('open_finish_day')->nullable()->comment('公開終了日');
            
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')    ->nullable()->comment('削除フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructor_courses');
    }
}
