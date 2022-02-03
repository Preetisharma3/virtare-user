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
        CREATE  PROCEDURE `getPatientCondition` (IN `idx` INT,IN `patientIdx` INT)  
        BEGIN
        SELECT globalCodes.name ,patientConditions.id AS conditionId,patientConditions.udid,patientConditions.createdAt,patientConditions.patientId
        FROM patientConditions LEFT JOIN globalCodes ON patientConditions.conditionId=globalCodes.id LEFT JOIN patients
        ON `patientConditions`.patientId=patients.id WHERE  (patientConditions.patientId = patientIdx) AND (patientConditions.id = idx OR idx = '') ;
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
