<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskStatusProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `taskStatusCount`";
        DB::unprepared($procedure);
        $procedure =
            "CREATE PROCEDURE `taskStatusCount`()
        BEGIN
        SELECT(IF((tasks.createdAt IS NULL),
            0,
            COUNT(tasks.id)
        )
    ) AS total,
    globalCodes.name AS text
FROM
    tasks
RIGHT JOIN globalCodes ON tasks.taskStatusId = globalCodes.id
WHERE
    globalCodes.globalCodeCategoryId = 5
GROUP BY
    globalCodes.id;
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
        Schema::dropIfExists('task_status_procedure');
    }
}