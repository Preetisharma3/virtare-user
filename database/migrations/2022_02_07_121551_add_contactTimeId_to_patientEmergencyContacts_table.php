<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactTimeIdToPatientEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patientEmergencyContacts', function (Blueprint $table) {
            $table->string('contactTimeId')->after('contactTypeId');
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
            $table->dropColumn('contactTimeId');

        });
    }
}
