<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfilePicInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD:database/migrations/2022_02_02_073610_create_get_patient_referals_procedure.php
        $procedure = "DROP PROCEDURE IF EXISTS `getPatientReferal`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE  PROCEDURE `getPatientReferal` (IN `idx` INT,IN `patientIdx` INT)  
        BEGIN
        SELECT globalCodes.name AS designation ,patientReferals.id AS referalId,patientReferals.udid,patientReferals.createdAt,patientReferals.patientId,patientReferals.name,patientReferals.phoneNumber,patientReferals.email,patientReferals.patientId,patientReferals.fax
        FROM patientReferals LEFT JOIN globalCodes ON patientReferals.designationId=globalCodes.id LEFT JOIN patients
        ON `patientReferals`.patientId=patients.id WHERE  (patientReferals.patientId = patientIdx) AND (patientReferals.id = idx OR idx = '');
        END;";
        DB::unprepared($procedure);
=======
        Schema::table('users', function (Blueprint $table) {
            $table->string('profilePhoto')->after('email');
        });
>>>>>>> main:database/migrations/2022_02_02_043339_add_profilePic_in_users_table.php
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profilePhoto');
        });
    }
}
