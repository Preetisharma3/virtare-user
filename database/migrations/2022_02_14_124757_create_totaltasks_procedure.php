<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotaltasksProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        $procedure = "DROP PROCEDURE IF EXISTS `totalTasksCount`";
        DB::unprepared($procedure);
        $procedure =
            "CREATE PROCEDURE `totalTasksCount`()
        BEGIN
        SELECT(IF((tasks.createdAt IS NULL),
            0,
            COUNT(tasks.id)
        )
    ) AS total,
    IF( COUNT(tasks.id) = 0,'#8E60FF','#8E60FF') AS color,
    IF( COUNT(tasks.id) = 0,'totalTasks','totalTasks') AS text
FROM
    tasks;
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
        Schema::dropIfExists('totaltasks_procedure');
    }
}