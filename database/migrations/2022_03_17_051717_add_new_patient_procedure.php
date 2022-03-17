<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewPatientProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $procedure = "DROP PROCEDURE IF EXISTS `getNewPatientCount`;
        CREATE PROCEDURE `getNewPatientCount`()
        BEGIN
        IF timelineId = 122 THEN
            SELECT
                COUNT(patients.id) AS total,
                'New' AS text,
                '#8E60FF' AS color,
                '#FFFFFF' AS textColor
                FROM patients
            WHERE
             patients.createdAt > date_sub(now(), interval 1 day) AND patients.deletedAt IS NULL 
            GROUP BY
            patients.Id;
        ELSEIF timelineId = 123 THEN
             SELECT
                COUNT(patients.id) AS total,
                'New' AS text,
                '#8E60FF' AS color,
                '#FFFFFF' AS textColor
                FROM patients
            WHERE patients.createdAt > date_sub(now(), interval 1 week) AND
            patients.deletedAt IS NULL 
            GROUP BY
            patients.Id;
        ELSEIF timelineId = 124 THEN
             SELECT
                COUNT(patients.id) AS total,
                'New' AS text,
                '#8E60FF' AS color,
                '#FFFFFF' AS textColor
                FROM patients
            WHERE
            patients.deletedAt IS NULL AND patients.createdAt > date_sub(now(), interval 1 month)
            GROUP BY
            patients.Id;
        ELSEIF timelineId = 125 THEN
             SELECT
                COUNT(patients.id) AS total,
                'New' AS text,
                '#8E60FF' AS color,
                '#FFFFFF' AS textColor
                FROM patients
            WHERE
            patients.deletedAt IS NULL AND patients.createdAt > date_sub(now(), interval 1 year)
            GROUP BY
            patients.Id;
        END IF;
        END";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('getNewPatientCount');
    }
}
