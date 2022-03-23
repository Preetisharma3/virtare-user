<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddProgramsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createAddPrograms = "DROP PROCEDURE IF EXISTS `createAddPrograms`;";

        DB::unprepared($createAddPrograms);

        $createAddPrograms = "
           CREATE PROCEDURE  createAddPrograms(IN data JSON) 
            BEGIN
            INSERT INTO programs (udid,description,typeId,name,createdBy) 
            values
             (JSON_UNQUOTE(JSON_EXTRACT(data, '$.udid')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.description')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.typeId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.name')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.createdBy')));
            END;";
  
        DB::unprepared($createAddPrograms);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_programs_procedure');
    }

}
