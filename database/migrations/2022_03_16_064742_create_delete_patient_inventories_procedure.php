<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletePatientInventoriesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $deletePatientInventories = "DROP PROCEDURE IF EXISTS `deletePatientInventories`;";

       

        $deletePatientInventories = "
        CREATE PROCEDURE  deletePatientInventories(IN idx VARCHAR(50)) 
        BEGIN
         DELETE FROM `patientinventories` WHERE id=idx;
        END;";
        

        DB::unprepared($deletePatientInventories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delete_patient_inventories_procedure');
    }
}
