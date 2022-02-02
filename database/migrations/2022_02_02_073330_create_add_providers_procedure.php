<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateAddProvidersProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `addProvider`";
        DB::unprepared($procedure);

        $procedure =
            "CREATE PROCEDURE `addProvider`(IN udid VARCHAR(255), IN name VARCHAR(50),IN address TEXT,IN countryId INT,IN stateId INT,IN city VARCHAR(30),IN zipcode VARCHAR(255),IN phoneNumber VARCHAR(255),IN tagId VARCHAR(255),IN moduleId VARCHAR(255),IN isActive TINYINT,IN createdBy int)
        BEGIN
        INSERT INTO providers 
        (udid,name,address,countryId,stateId,city,zipcode,phoneNumber,tagId,moduleId,isActive,createdBy) 
        values
        (udid,name,address,countryId,stateId,city,zipcode,phoneNumber,tagId,moduleId,isActive,createdBy);
        SELECT LAST_INSERT_ID() as id from providers;
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
        Schema::dropIfExists('addProviders_procedure');
    }
}
