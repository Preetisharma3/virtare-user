<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientVitalProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientVital`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE PROCEDURE `getPatientVital`(In idx INT,In patientIdx INT)
        BEGIN
        SELECT patientVitals.*, vitalFields.name vitalFieldName,
        globalCodes.name AS deviceName
        FROM patientVitals 
        LEFT JOIN vitalFields 
        ON patientVitals.vitalFieldId=vitalFields.id 
        RIGHT JOIN vitalTypeFields 
        ON vitalFields.id=vitalTypeFields.vitalFieldId 
        LEFT JOIN globalCodes 
        ON vitalTypeFields.vitalTypeId=globalCodes.id 
        LEFT JOIN patients
        ON `patientVitals`.patientId=patients.id 
        WHERE (patientVitals.patientId = patientIdx) 
        AND (patientVitals.id = idx OR idx = '')
        ORDER BY patientVitals.takeTime DESC;
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
        Schema::dropIfExists('get_patient_vital_procedure');
    }
}
