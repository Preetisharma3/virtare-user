<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientReferalsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientReferal`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE  PROCEDURE `getPatientReferal` (IN `idx` INT,IN `patientIdx` INT)  
        BEGIN
        SELECT globalCodes.name AS designation ,patientReferals.id AS referalId,patientReferals.udid,patientReferals.createdAt,patientReferals.patientId,patientReferals.name,patientReferals.phoneNumber,patientReferals.email,patientReferals.patientId,patientReferals.fax
        FROM patientReferals LEFT JOIN globalCodes ON patientReferals.designationId=globalCodes.id LEFT JOIN patients
        ON `patientReferals`.patientId=patients.id WHERE  (patientReferals.patientId = patientIdx) AND (patientReferals.id = idx OR idx = '');
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
        Schema::dropIfExists('get_patient_referals_procedure');
    }
}
