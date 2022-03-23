<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetPatientInventoriesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientInventories`;
        CREATE PROCEDURE `getPatientInventories`(IN patientIdx INT, IN inventoryIdx VARCHAR(100))
        BEGIN
        SELECT * ,
            patients.firstName as patientId,
            patientinventories.udid as udid
            FROM patientinventories
            JOIN patients ON patientinventories.patientId = patients.id
            WHERE patientinventories.patientId = patientIdx
            AND (patientinventories.udid = inventoryIdx OR inventoryIdx ='');
        END;";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_patient_inventories_procedure');
    }
}
