<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPatientProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createAddPatientPrograms = "DROP PROCEDURE IF EXISTS `createAddPatientPrograms`;";

        DB::unprepared($createAddPatientPrograms);

        $createAddPatientPrograms = "
           CREATE PROCEDURE  createAddPatientPrograms(IN data JSON) 
            BEGIN
            INSERT INTO patientprograms (udid,programId,patientId,onboardingScheduleDate,dischargeDate,createdBy) 
            values
             (JSON_UNQUOTE(JSON_EXTRACT(data, '$.udid')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.programId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.patientId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.onboardingScheduleDate')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.dischargeDate')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.createdBy')));
            END;";
  
        DB::unprepared($createAddPatientPrograms);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_add_patient_programs_procedure');
    }
}
