<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeleteProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $deletePrograms = "DROP PROCEDURE IF EXISTS `deletePrograms`;";

        DB::unprepared($deletePrograms);

        $deletePrograms = "
        CREATE PROCEDURE  deletePrograms(IN idx VARCHAR(50)) 
        BEGIN
        DELETE FROM `programs` WHERE programs.udid = idx;
        END;";
        
        DB::unprepared($deletePrograms);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delete_programs_procedure');
    }
}
