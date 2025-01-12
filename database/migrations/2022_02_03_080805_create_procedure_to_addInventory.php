<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureToAddInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `createInventories`";

        DB::unprepared($procedure);

        $procedure =
            'CREATE PROCEDURE  createInventories(IN data JSON) 
        BEGIN
        INSERT INTO inventories 
        (udid,deviceModelId,serialNumber,macAddress,isActive,createdBy) 
        values
        (JSON_UNQUOTE(JSON_EXTRACT(data, "$.udid")),JSON_UNQUOTE(JSON_EXTRACT(data, "$.deviceModelId")),JSON_UNQUOTE(JSON_EXTRACT(data, "$.serialNumber")),JSON_UNQUOTE(JSON_EXTRACT(data, "$.macAddress")),JSON_UNQUOTE(JSON_EXTRACT(data, "$.isActive")),JSON_UNQUOTE(JSON_EXTRACT(data, "$.createdBy")));
        END;';

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedure_to_addInventory');
    }
}
