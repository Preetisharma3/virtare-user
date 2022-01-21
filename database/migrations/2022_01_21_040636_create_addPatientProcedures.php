<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateAddPatientProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $sql = <<<SQL
         DROP PROCEDURE IF EXISTS addPatient;
         CREATE PROCEDURE addPatient(IN id BIGINT(20),udid VARCHAR(255),firstName VARCHAR(25), middleName VARCHAR(25), lastName VARCHAR(25), dob DATE, genderId BIGINT(20),
         otherLanguageId BIGINT(20), contactTypeId BIGINT(20), languageId BIGINT(20), nickName VARCHAR(25), height VARCHAR(10), weight VARCHAR(10), userId BIGINT(20), 
         phoneNumber VARCHAR(20), contactTimeId BIGINT(20), medicalRecordNumber VARCHAR(30), countryId BIGINT(20), stateId BIGINT(20), city VARCHAR(50), 
         zipCode VARCHAR(10), appartment VARCHAR(20), address VARCHAR(200))
         BEGIN
            INSERT INTO `patients` (`id`, `udid`, `firstName`, `middleName`, `lastName`, `dob`, `genderId`, `otherLanguageId`, `contactTypeId`, 
            `languageId`, `nickName`, `height`, `weight`, `userId`, `phoneNumber`, `contactTimeId`, `medicalRecordNumber`, `countryId`, `stateId`, 
            `city`, `zipCode`, `appartment`, `address`, `isActive`, `isDelete`, `createdBy`, `updatedBy`, `deletedBy`, `createdAt`, `updatedAt`, `deletedAt`) 
            VALUES(1, 'd76ad323-cd1b-4bcf-ae3d-2300daa1ea17', 'john', 'Downey', 'Junior', '1982-02-12', 1, '\"[{\\\"12\\\"}]\"',
             '\"[{\\\"14\\\",\\\"15\\\"}]\"', 11, 'tony', '5\'11', '190 lbs', 2, '123-222-2222', 16, '1223', 19, 22, 'Olympia', '1232', 
             '#0787', 'Olympia Apartment 0787', 1, 0, 1, NULL, NULL, '2022-01-19 12:10:05', '2022-01-19 12:10:05', NULL)
         END
         SQL;


                 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
