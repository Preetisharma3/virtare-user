<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSearchProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `patientSearch`;";
        DB::unprepared($procedure);

        $procedure = "CREATE PROCEDURE `patientSearch`(IN search VARCHAR(20))
        BEGIN
        SELECT *
        FROM   `communications`
        WHERE  (
                      EXISTS
                      (
                             SELECT *
                             FROM   `staffs`
                             WHERE  `communications`.`FROM` = `staffs`.`id`
                             AND    match(firstName)against(search)
                             AND    `staffs`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT *
                             FROM   `patients`
                             WHERE  `communications`.`referenceid` = `patients`.`id` AND
                          `communications`.`entityType` = 'patient'
                             AND    match(firstName)against(search)
                             AND    `patients`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT *
                             FROM   `globalCodes`
                             WHERE  `communications`.`messagetypeid` = `globalCodes`.`id`
                             AND    match(Name)against(search)
                             AND    `globalCodes`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT *
                             FROM   `globalCodes`
                             WHERE  `communications`.`priorityid` = `globalCodes`.`id`
                             AND    match(Name)against(search)
                             AND    `globalCodes`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT *
                             FROM   `globalCodes`
                             WHERE  `communications`.`messagecategoryid` = `globalCodes`.`id`
                             AND    match(Name)against(search)
                             AND    `globalCodes`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT *
                             FROM   `staffs`
                             WHERE  `communications`.`referenceid` = `staffs`.`id` AND
                          `communications`.`entityType` = 'staff'
                             AND    match(firstName)against(search)
                             AND    `staffs`.`deletedat` IS NULL))
        AND    `communications`.`deletedat` IS NULL;
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
        Schema::dropIfExists('patient_search_procedure');
    }
}

