<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetPatientProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $userdata =  "DROP PROCEDURE IF EXISTS `getPatientPrograms`;
      CREATE PROCEDURE `getPatientPrograms`(IN programId int)
      BEGIN
      SELECT ,programs.name FROM patientprograms
      LEFT JOIN programs ON patientprograms.programId = programs.id
      WHERE patientprograms.udid = programId;
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
        Schema::dropIfExists('get_patient_programs_procedure');
    }
}
