<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatePatientProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $userdata =  "DROP PROCEDURE IF EXISTS `updatePatientPrograms`;
      CREATE PROCEDURE `updatePatientPrograms`( 
                                            IN id int,
                                            IN programId int,
                                            IN onboardingScheduleDate DATE,
                                            IN dischargeDate DATE,
                                            IN isActive int,
                                            IN updatedBy int)
      BEGIN
        UPDATE
        patientprograms
                    SET
                        programId = programId,
                        onboardingScheduleDate = onboardingScheduleDate,
                        dischargeDate = dischargeDate,
                        updatedBy = updatedBy
                    WHERE
                        patientprograms.id = id;
                    END;";
      DB::unprepared($userdata); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_update_patient_programs_procedure');
    }
}
