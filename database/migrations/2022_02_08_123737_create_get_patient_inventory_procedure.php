<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientInventoryProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientInventory`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE PROCEDURE `getPatientInventory`(In idx INT)
        BEGIN
        SELECT patientInventories.id AS patientInventoryId, patientInventories.udid AS patientInventoryUdid,patientInventories.isAdded AS patientInventoryIsAdded,patientInventories.inventoryId AS inventoryId,patientInventories.patientId AS PatientId,
        inventories.serialNumber,inventories.macAddress,inventories.isAvailable,inventories.isActive AS inventoryIsActive,deviceModels.modelName,globalCodes.name AS deviceType
        FROM patientInventories
        LEFT JOIN inventories
        ON patientInventories.inventoryId=inventories.id
        LEFT JOIN patients
        ON patientInventories.patientId=patients.id
        LEFT JOIN deviceModels
        ON inventories.deviceModelId=deviceModels.id
        LEFT JOIN globalCodes
        ON deviceModels.deviceTypeId=globalCodes.id
        WHERE patientInventories.patientId=patientIdx AND
        (patientInventories.id=idx OR idx='');
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
        Schema::dropIfExists('get_patient_inventory_procedure');
    }
}
