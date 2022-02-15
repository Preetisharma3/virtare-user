<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsToPatientEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patientEmergencyContacts', function (Blueprint $table) {
            $table->dropForeign('patients_contactTypeId_foreign');
            $table->dropColumn('contactTypeId');
            $table->dropForeign('patients_contactTimeId_foreign');
            $table->dropColumn('contactTimeId');
            $table->dropForeign('patients_genderId_foreign');
            $table->dropColumn('genderId');
            $table->dropForeign('patients_patientId_foreign');
            $table->dropColumn('patientId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patientEmergencyContacts', function (Blueprint $table) {
            $table->bigInteger('contactTypeId')->unsigned();
            $table->foreign('contactTypeId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('contactTimeId')->unsigned();
            $table->foreign('contactTimeId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('genderId')->unsigned();
            $table->foreign('genderId')->references('id')->on('globalCodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('patientId')->unsigned();
            $table->foreign('patientId')->references('id')->on('patients')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
