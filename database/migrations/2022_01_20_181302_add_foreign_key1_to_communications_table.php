<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKey1ToCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->bigInteger('providerId')->unsigned()->nullable()->after('udid');
            $table->bigInteger('staffId')->unsigned()->nullable()->after('udid');
            $table->foreign('providerId')->references('id')->on('providers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('staffId')->references('id')->on('staffs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communications', function (Blueprint $table) {
            //
        });
    }
}
