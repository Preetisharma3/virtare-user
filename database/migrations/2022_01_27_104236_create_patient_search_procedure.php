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
SELECT staffFrom.firstName AS staffFromName,patients.firstName AS patientName,globalPriority.name AS priorityName,
        globalMessage.name AS messageName,globalCodeCategory.name AS categoryName,staffReference.firstName AS staffReference,
         communications.createdAt AS communicationCreateDate, communications.id AS communicationId, communications.entityType AS entity
        FROM   `communications`
         JOIN globalCodes AS globalMessage
        ON communications.messageTypeId  = globalMessage.id 
         JOIN staffs AS staffFrom 
        ON communications.from  = staffFrom.id 
         JOIN patients 
        ON communications.referenceid  = patients.id 
         JOIN globalCodes AS globalPriority
        ON communications.priorityid  = globalPriority.id 
         JOIN globalCodes AS globalCodeCategory
        ON communications.messagecategoryid  = globalCodeCategory.id 
         JOIN staffs AS staffReference 
        ON communications.referenceid  = staffReference.id 
        WHERE  (
                      EXISTS
                      (
                             SELECT staffs.firstName AS staffName
                             FROM   `staffs`
                             WHERE  `communications`.`FROM` = `staffs`.`id`
                             AND    match(firstName)against(search)
                             AND    `staffs`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT patients.firstName AS patientName
                             FROM   `patients`
                             WHERE  `communications`.`referenceid` = `patients`.`id` AND
                          `communications`.`entityType` = 'patient'
                             AND    match(firstName)against(search)
                             AND    `patients`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT globalCodes.name AS messageTypeName
                             FROM   `globalCodes`
                             WHERE  `communications`.`messagetypeid` = `globalCodes`.`id`
                             AND    match(Name)against(search)
                             AND    `globalCodes`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT globalCodes.name AS priorityName
                             FROM   `globalCodes`
                             WHERE  `communications`.`priorityid` = `globalCodes`.`id`
                             AND    match(Name)against(search)
                             AND    `globalCodes`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT globalCodes.name AS messageCategoryName
                             FROM   `globalCodes`
                             WHERE  `communications`.`messagecategoryid` = `globalCodes`.`id`
                             AND    match(Name)against(search)
                             AND    `globalCodes`.`deletedat` IS NULL)
               OR     EXISTS
                      (
                             SELECT staffs.firstName AS staffName
                             FROM   `staffs`
                             WHERE  `communications`.`referenceid` = `staffs`.`id` AND
                          `communications`.`entityType` = 'staff'
                             AND    match(firstName)against(search)
                             AND    `staffs`.`deletedat` IS NULL))
<<<<<<< HEAD
        AND    `communications`.`deletedat` IS NULL
        LIMIT limit_val;
=======
        AND    `communications`.`deletedat` IS NULL;
>>>>>>> main
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

