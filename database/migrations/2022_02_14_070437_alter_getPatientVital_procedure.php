<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGetPatientVitalProcedure extends Migration
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
        CREATE PROCEDURE `getPatientVital`(In patientIdx INT,IN fromDate VARCHAR(100),IN toDate VARCHAR(100),IN type VARCHAR(100))
        BEGIN
            IF type = '' AND fromDate = '' AND toDate = '' THEN
            SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            patientVitals.createdType AS createdType,
            patientVitals.comment AS comment,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            WHERE patientVitals.patientId = patientIdx
            ORDER BY patientVitals.takeTime DESC;
        ELSEIF fromDate = '' AND toDate = '' THEN
            SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            patientVitals.createdType AS createdType,
            patientVitals.comment AS comment,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            WHERE patientVitals.patientId = patientIdx AND patientVitals.vitalFieldId = type 
            ORDER BY patientVitals.takeTime DESC;
        ELSEIF type = '' THEN
            SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            patientVitals.createdType AS createdType,
            patientVitals.comment AS comment,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            WHERE patientVitals.patientId = patientIdx AND patientVitals.takeTime >= fromDate AND patientVitals.takeTime <= toDate
            ORDER BY patientVitals.takeTime DESC;
        ELSEIF toDate = '' THEN
            SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            patientVitals.createdType AS createdType,
            patientVitals.comment AS comment,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            WHERE patientVitals.patientId = patientIdx AND patientVitals.takeTime >= fromDate AND patientVitals.vitalFieldId = type
            ORDER BY patientVitals.takeTime DESC;
        ELSEIF fromDate = '' AND toDate = '' AND type = '' THEN
            SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            patientVitals.createdType AS createdType,
            patientVitals.comment AS comment,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            WHERE patientVitals.patientId = patientIdx 
            ORDER BY patientVitals.takeTime DESC;
        ELSE
            SELECT patientVitals.*,
            vitalFields.name AS vitalField,
            patientVitals.value AS value,
            patientVitals.units AS units,
            patientVitals.takeTime AS takeTime,
            patientVitals.startTime AS startTime,
            patientVitals.endTime AS endTime,
            patientVitals.addType AS addType,
            patientVitals.createdType AS createdType,
            patientVitals.comment AS comment,
            patientVitals.createdAt AS lastReadingDate,
            patientVitals.deviceInfo AS deviceInfo
            FROM patientVitals
            JOIN vitalFields ON patientVitals.vitalFieldId=vitalFields.id 
            WHERE patientVitals.patientId = patientIdx AND patientVitals.takeTime >= fromDate AND patientVitals.takeTime <= toDate AND patientVitals.vitalFieldId = type
            ORDER BY patientVitals.takeTime DESC;
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
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientVital`;";
        DB::unprepared($procedure);
    }
}
