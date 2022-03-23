<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeleteUpdateProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $deletePatientPrograms = "DROP PROCEDURE IF EXISTS `deletePatientPrograms`;";

        $deletePatientPrograms = "
        CREATE PROCEDURE  deletePatientPrograms(IN idx VARCHAR(50)) 
        BEGIN
        DELETE
        FROM
        patientprograms
        WHERE
        patientprograms.udid = idx;
        END;";
        
        DB::unprepared($deletePatientPrograms);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delete_update_procedure');
    }
}

