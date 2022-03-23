<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatePatientInventoriesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $userdata =  "DROP PROCEDURE IF EXISTS `updatePatientInventories`;
      CREATE PROCEDURE `updatePatientInventories`( 
                                            IN id int,
                                            IN inventoryId int,
                                            IN patientId int,
                                            IN updatedBy int)
      BEGIN
        UPDATE
        patientinventories
                    SET
                        inventoryId = inventoryId,
                        patientId = patientId,
                        updatedBy = updatedBy
                    WHERE
                        patientinventories.id = id;
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
        Schema::dropIfExists('_update_patient_inventories_procedure');
    }
}
