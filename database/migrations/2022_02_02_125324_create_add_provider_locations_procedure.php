<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddProviderLocationsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `addProviderLocations`";
        DB::unprepared($procedure);

        $procedure =
            "CREATE PROCEDURE `addProviderLocations`(IN udid VARCHAR(255),IN providerId INT, IN locationName VARCHAR(50),numberOfLocations VARCHAR(50),IN locationAddress TEXT,IN stateId INT,IN city VARCHAR(30),IN zipCode VARCHAR(255),IN phoneNumber VARCHAR(255),IN email  VARCHAR(50),IN websiteUrl TEXT,IN isDefault TINYINT,IN isActive TINYINT,IN createdBy int)
        BEGIN
        INSERT INTO providerLocations 
        (udid,providerId,locationName,numberOfLocations,locationAddress,stateId,city,zipCode,phoneNumber,email ,websiteUrl,isDefault,isActive,createdBy) 
        values
        (udid,providerId,locationName,numberOfLocations,locationAddress,stateId,city,zipCode,phoneNumber,email ,websiteUrl,isDefault,isActive,createdBy);
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
        Schema::dropIfExists('add_provider_locations_procedure');
    }
}
