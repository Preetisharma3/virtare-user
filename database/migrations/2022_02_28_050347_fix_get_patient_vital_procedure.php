<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class FixGetPatientVitalProcedure extends Migration
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
        CREATE PROCEDURE `getPatientVital`(In patientIdx INT,IN fromDate VARCHAR(100),IN toDate VARCHAR(100),IN type VARCHAR(100),IN deviceType VARCHAR(100))
        BEGIN
        SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            globalCodes.name AS deviceType,
            patientVitals.createdType AS createdType,
            notes.note AS note,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            JOIN notes ON patientVitals.id=notes.referenceId
            JOIN globalCodes ON patientVitals.deviceTypeId=globalCodes.id
            WHERE patientVitals.patientId = patientIdx
            AND (patientVitals.takeTime >= fromDate OR fromDate = '')
            AND (patientVitals.takeTime <= toDate OR toDate = '')
            AND (patientVitals.deviceTypeId = deviceType OR deviceType = '')
            AND (patientVitals.vitalFieldId = type OR type = '')
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
        //
    }
}
