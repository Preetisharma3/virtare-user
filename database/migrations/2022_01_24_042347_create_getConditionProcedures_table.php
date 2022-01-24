<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateGetConditionProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getConditionById`;
        CREATE PROCEDURE `getConditionById` (IN id int)
        BEGIN
        SELECT *
        FROM patientConditions WHERE id = idx;
        LEFT JOIN patients
        ON patientConditions.patientId = patients.id
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
