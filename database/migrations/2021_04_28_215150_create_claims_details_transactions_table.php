<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsDetailsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims_details_transactions', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->integer('claim_trn_id')                ->comment('請求ID');
            $table->integer('parent_id')                   ->comment('親請求明細ID');
            $table->string('item_name','100')->nullable()  ->comment('項目');
            $table->integer('unit_price')    ->default('0')->comment('単価');
            $table->integer('quantity')      ->default('0')->comment('数量');
            $table->string('unit')           ->nullable()  ->comment('単位');
            $table->integer('price')         ->default('0')->comment('金額');
            $table->integer('rank')                        ->comment('並び順');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });
        DB::statement("ALTER TABLE payments COMMENT '請求明細情報トラン'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claims_details_transactions');
    }
}
