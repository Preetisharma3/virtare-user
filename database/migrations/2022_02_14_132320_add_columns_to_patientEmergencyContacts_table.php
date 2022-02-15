<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPatientEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patientEmergencyContacts', function (Blueprint $table) {
            $table->integer('contactTypeId')->nullable();
            $table->integer('contactTimeId')->nullable();
            $table->integer('genderId')->nullable();
            $table->integer('patientId')->nullable();
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
            $table->dropColumn('contactTypeId');
            $table->dropColumn('contactTimeId');
            $table->dropColumn('genderId');
            $table->dropColumn('patientId');
        });
    }
}
