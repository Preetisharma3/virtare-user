<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign('patients_genderId_foreign');
            $table->dropColumn('genderId');
            $table->dropForeign('patients_otherLanguageId_foreign');
            $table->dropColumn('otherLanguageId');
            $table->dropForeign('patients_languageId_foreign');
            $table->dropColumn('languageId');
            $table->dropForeign('patients_contactTypeId_foreign');
            $table->dropColumn('contactTypeId');
            $table->dropForeign('patients_contactTimeId_foreign');
            $table->dropColumn('contactTimeId');
            $table->dropForeign('patients_countryId_foreign');
            $table->dropColumn('countryId');
            $table->dropForeign('patients_stateId_foreign');
            $table->dropColumn('stateId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->bigInteger('genderId')->unsigned();
            $table->foreign('genderId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('otherLanguageId')->unsigned();
            $table->foreign('otherLanguageId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('languageId')->unsigned();
            $table->foreign('languageId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('contactTypeId')->unsigned();
            $table->foreign('contactTypeId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('contactTimeId')->unsigned();
            $table->foreign('contactTimeId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('countryId')->unsigned();
            $table->foreign('countryId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('stateId')->unsigned();
            $table->foreign('stateId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
