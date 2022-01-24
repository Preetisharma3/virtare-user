<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceIdToScreenActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('screen_actions', function (Blueprint $table) {
            $table->bigInteger('deviceId')->unsigned()->nullable()->after('actionId');
            $table->foreign('deviceId')->references('id')->on('devices')->onUpdate('cascade')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('screen_actions', function (Blueprint $table) {
            //
        });
    }
}
