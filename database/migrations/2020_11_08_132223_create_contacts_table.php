<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->comment('顧客ID');
            $table->timestamp('history_datetime')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('実施日時')	;
            $table->tinyInteger('means_id')->nullable()->comment('通話手段');
            $table->tinyInteger('result_id')->nullable()->comment('結果');
            $table->tinyInteger('staff')->nullable()->comment('ユーザー');
            $table->string('recipient_name')->nullable()->comment('受電者名');
            $table->string('recipient_role')->nullable()->comment('受電者役職');
            $table->string('recipient_sex')->nullable()->comment('受電者性別');
            $table->string('person_charge_name')->nullable()->comment('担当者名');
            $table->string('person_charge_role')->nullable()->comment('担当者役職');
            $table->string('person_charge_sex')->nullable()->comment('担当者性別');
            $table->string('history_detail','500')->nullable()->comment('履歴内容');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');

            // 外部キー制約
            $table->foreign('client_id')->references('id')->on('clients');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
