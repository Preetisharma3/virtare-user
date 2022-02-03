<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientPhysicianProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientPhysician`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE  PROCEDURE `getPatientPhysician` (IN `idx` INT,IN `patientIdx` INT)  
        BEGIN
        SELECT globalCodes.name AS designation ,patientPhysicians.id AS referalId,patientPhysicians.udid,patientReferals.createdAt,patientReferals.isActive,patientReferals.isDelete,patientReferals.createdBy,patientReferals.updatedBy,patientReferals.deletedBy,patientReferals.updatedAt,patientReferals.deletedAt,patientReferals.patientId,patientReferals.name,patientReferals.phoneNumber,patientReferals.email,patientReferals.patientId,patientReferals.fax
        FROM patientPhysicians 
        LEFT JOIN globalCodes ON patientPhysicians.designationId=globalCodes.id LEFT JOIN patients
        ON `patientPhysicians`.patientId=patients.id 
        WHERE  (patientPhysicians.patientId = patientIdx) 
        AND (patientPhysicians.id = idx OR idx = '');
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
        Schema::dropIfExists('get_patient_physician_procedure');
    }
}
