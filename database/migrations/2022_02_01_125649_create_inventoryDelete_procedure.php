<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDeleteProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $screenAction = "DROP PROCEDURE IF EXISTS `deleteInventory`;
        CREATE PROCEDURE  deleteInventory(IN id int) 
        BEGIN
        DELETE FROM `inventories` WHERE id=id;
        END;";

        DB::unprepared($screenAction);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_delete_procedure');
    }
}
