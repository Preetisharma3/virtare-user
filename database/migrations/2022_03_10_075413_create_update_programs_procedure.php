<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
       
        $userdata =  "DROP PROCEDURE IF EXISTS `updatePrograms`;
      CREATE PROCEDURE `updatePrograms`( 
                                            IN id int,
                                            IN description VARCHAR(50),
                                            IN typeId VARCHAR(50),
                                            IN name VARCHAR(50),
                                            IN isActive int,
                                            IN updatedBy int)
      BEGIN
        UPDATE
        programs
                    SET
                        description = description,
                        typeId = typeId,
                        name = name,
                        updatedBy = updatedBy
                    WHERE
                        programs.id = id;
                    END;";
      DB::unprepared($userdata); 
    }

    public function down()
    {
        Schema::dropIfExists('update_programs_procedure');
    }

}
