<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientsProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatients`;
        CREATE PROCEDURE `getPatients` (IN id int)
        BEGIN
        SELECT *
        FROM patients
        LEFT JOIN vitalFields
        ON patients.id = vitalFields.patientId
        LEFT JOIN patientFlags
        ON patients.id = patientFlags.patientId;
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
    }
}
