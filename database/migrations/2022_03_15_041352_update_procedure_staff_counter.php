<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProcedureStaffCounter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `careCoordinatorNetworkCount`";
        DB::unprepared($procedure);
        $procedure =
            "CREATE PROCEDURE `careCoordinatorNetworkCount`()
        BEGIN
        SELECT(IF((staffs.createdAt IS NULL),
            0,
            COUNT(staffs.id)
        )
    ) AS total,
    globalCodes.name AS text
FROM
    staffs
RIGHT JOIN globalCodes ON staffs.networkId = globalCodes.id
WHERE
    globalCodes.globalCodeCategoryId = 10
AND
    staffs.deletedAt IS NULL AND globalCodes.deletedAt IS NULL
GROUP BY
    globalCodes.id;
        END;";
        DB::unprepared($procedure);


         $procedure = "DROP PROCEDURE IF EXISTS `careCoordinatorSpecializationCount`";
        DB::unprepared($procedure);
        $procedure =
            "CREATE PROCEDURE `careCoordinatorSpecializationCount`()
        BEGIN
        SELECT(IF((staffs.createdAt IS NULL),
            0,
            COUNT(staffs.id)
        )
    ) AS total,
    globalCodes.name AS text
FROM
    staffs
RIGHT JOIN globalCodes ON staffs.specializationId = globalCodes.id
WHERE
    globalCodes.globalCodeCategoryId = 2
AND
    staffs.deletedAt IS NULL AND globalCodes.deletedAt IS NULL
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
        //
    }
}
