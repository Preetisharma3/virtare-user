<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromProviderLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providerLocations', function (Blueprint $table) {
            $table->dropForeign('providerLocations_cityId_foreign');
            $table->dropColumn('cityId');
            $table->string('city',30)->after('stateId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_locations', function (Blueprint $table) {
            $table->bigInteger('cityId')->unsigned();
            $table->foreign('cityId')->references('id')->on('providerLocations')->onDelete('cascade')->onUpdate('cascade');
            $table->dropColumn('city');
        });
    }
}
