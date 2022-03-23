<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPatientInventoriesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $createAddPatientInventories = "DROP PROCEDURE IF EXISTS `createAddPatientInventories`;";

        DB::unprepared($createAddPatientInventories);

        $createAddPatientInventories = "
           CREATE PROCEDURE  createAddPatientInventories(IN data JSON) 
            BEGIN
            INSERT INTO patientinventories (udid,inventoryId,patientId,createdBy) 
            values
             (JSON_UNQUOTE(JSON_EXTRACT(data, '$.udid')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.inventoryId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.patientId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.createdBy')));
            END;";
  
        DB::unprepared($createAddPatientInventories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_add_patient_inventories_procedure');
    }
}
