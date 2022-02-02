<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientConditionProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientCondition`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE PROCEDURE `getPatientCondition`(IN idx INT(11)= NULL)
        BEGIN
         IF (idx IS NULL)  
SELECT globalCodes.name ,patientConditions.id,patientConditions.udid,
patientConditions.createdAt,patientConditions.patientId
     FROM patientConditions
      LEFT JOIN globalCodes 
        ON patientConditions.conditionId=globalCodes.id
        LEFT JOIN patients 
        ON patientConditions.patientId=patients.id
   ELSE 
    SELECT globalCodes.name ,patientConditions.id,patientConditions.udid,
patientConditions.createdAt,patientConditions.patientId
     FROM patientConditions WHERE id=idx
      LEFT JOIN globalCodes 
        ON patientConditions.conditionId=globalCodes.id
        LEFT JOIN patients 
        ON patientConditions.patientId=patients.id
   END IF;  
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
        Schema::dropIfExists('get_patient_condition_procedure');
    }
}
