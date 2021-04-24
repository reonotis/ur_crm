<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->integer('instructor_id')               ->comment('インストラクターID');
            $table->integer('amount')                      ->comment('金額');
            $table->string('item_name')                    ->comment('項目名');
            $table->string('comment')                      ->comment('コメント');
            $table->date('deadline')          ->nullable() ->comment('期日');
            $table->date('accounting_date')   ->nullable() ->comment('計上日');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')->default('0')->comment('削除フラグ');
        });
        DB::statement("ALTER TABLE payments COMMENT 'インストラクターの年会費を管理'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
