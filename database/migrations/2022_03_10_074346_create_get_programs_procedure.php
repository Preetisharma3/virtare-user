<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        $userdata =  "DROP PROCEDURE IF EXISTS `getPrograms`;
      CREATE PROCEDURE `getPrograms`()
      BEGIN
      SELECT * FROM programs;
      END;";
      DB::unprepared($userdata); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_programs_procedure');
    }
}
