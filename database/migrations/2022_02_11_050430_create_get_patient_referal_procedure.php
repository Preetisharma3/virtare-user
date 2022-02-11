<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientReferalProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientReferal`";
        DB::unprepared($procedure);
        $procedure =
        "CREATE PROCEDURE `getPatientReferal`(In patientIdx INT,IN typeVital VARCHAR(20))
        BEGIN
        SELECT patientReferals.id AS patientReferalId,patientReferals.udid AS patientReferalUdid,patientReferals.name,patientReferals.phoneNumber,patientReferals.email,patientReferals.patientId AS patientReferalPatientId,patientReferals.fax from patientReferals
        LEFT JOIN globalCodes
        ON patientReferals.designationId=globalCodes.id
        LEFT JOIN patients
        ON `patientReferals`.patientId=patients.id 
        WHERE  (patientReferals.patientId = patientIdx) 
        AND (patientReferals.id = idx OR idx = '');
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
        Schema::dropIfExists('get_patient_referal_procedure');
    }
}
