<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixIssueInventoryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `inventoryList`";
        DB::unprepared($procedure);

        $procedure =
            "CREATE PROCEDURE `inventoryList`(IN isAvailable TINYINT,IN deviceType INT,IN active INT)
       BEGIN
        SELECT
        inventories.*,
        inventories.udid AS udid,
        deviceModels.modelName AS modelNumber,
        globalCodes.name AS deviceType,
        globalCodes.id AS deviceTypeId,
        deviceModels.modelName AS modelNumber,
        inventories.serialNumber AS serialNumber,
        inventories.macAddress AS macAddress
        FROM inventories  
        INNER JOIN deviceModels ON deviceModels.id = inventories.deviceModelId 
        INNER JOIN globalCodes ON globalCodes.id = deviceModels.deviceTypeId
        WHERE (inventories.isAvailable = isAvailable OR isAvailable='')
        AND (inventories.isActive=active OR active='')
        AND (deviceModels.deviceTypeId = deviceType OR deviceType='');
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
        //
    }
}
