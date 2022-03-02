<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolePermissionListingProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $procedure = "DROP PROCEDURE IF EXISTS `rolePermissionListing`";
        DB::unprepared($procedure);

        $procedure =
            "CREATE PROCEDURE `rolePermissionListing`(IN idx INT)
    BEGIN
    SELECT
    accessRoleId AS roleId,
    accessRoles.roles AS role,
    GROUP_CONCAT(
        DISTINCT modules.id
    ORDER BY
        screenId SEPARATOR ', '
    ) AS moduleId,
    GROUP_CONCAT(
        DISTINCT modules.name
    ORDER BY
        screenId SEPARATOR ', '
    ) AS moduleName,
    GROUP_CONCAT(
        DISTINCT screens.id
    ORDER BY
        actionId SEPARATOR ', '
    ) AS screenId,
    GROUP_CONCAT(
        DISTINCT screens.name
    ORDER BY
        actionId SEPARATOR ', '
    ) AS screenName,
    GROUP_CONCAT(
        DISTINCT actionId
    ORDER BY
        actionId SEPARATOR ', '
    ) AS actionId,
    GROUP_CONCAT(
        DISTINCT actions.name
    ORDER BY
    actionId SEPARATOR ', '
    ) AS actionName,
    GROUP_CONCAT(
        DISTINCT actions.controller
    ORDER BY
    actionId SEPARATOR ', '
    ) AS actionController,
    GROUP_CONCAT(
        DISTINCT actions.function
    ORDER BY
    actionId SEPARATOR ', '
    ) AS actionFunction
FROM
    rolePermissions
JOIN actions ON actions.id = rolePermissions.actionId
JOIN accessRoles ON accessRoles.id = rolePermissions.accessRoleId
JOIN screens ON screens.id = actions.screenId
JOIN modules ON modules.id = screens.moduleId
WHERE
          rolePermissions.accessRoleId = idx
GROUP BY
    accessRoleId;
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
