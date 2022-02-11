<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientProgramProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientProgram`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE  PROCEDURE `getPatientProgram` (IN `idx` VARCHAR(50),IN `patientIdx` INT)  
        BEGIN
        SELECT patientPrograms.id AS patientProgramId,patientPrograms.udid AS patientProgramUdid,patientPrograms.onboardingScheduleDate,patientPrograms.dischargeDate,patientPrograms.isActive,patientPrograms.createdAt,globalCodes.name
        FROM patientPrograms 
        LEFT JOIN programs 
        ON patientPrograms.programtId=programs.id
        LEFT JOIN globalCodes
        ON programs.typeId=globalCodes.id
        LEFT JOIN patients
        ON `patientPrograms`.patientId=patients.id 
        WHERE  (patientPrograms.patientId = patientIdx) 
        AND (patientPrograms.udid = idx OR idx = '');
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
        Schema::dropIfExists('get_patient_program_procedure');
    }
}
