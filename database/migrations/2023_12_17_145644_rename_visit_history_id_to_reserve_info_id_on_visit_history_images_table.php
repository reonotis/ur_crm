<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameVisitHistoryIdToReserveInfoIdOnVisitHistoryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Laravel 10出ない場合、これ必須です composer require "doctrine/dbal:2.*"

        Schema::table('visit_history_images', function (Blueprint $table) {
            $table->renameColumn('visit_history_id', 'reserve_info_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_history_images', function (Blueprint $table) {
            $table->renameColumn('reserve_info_id', 'visit_history_id');
            //
        });
    }
}


