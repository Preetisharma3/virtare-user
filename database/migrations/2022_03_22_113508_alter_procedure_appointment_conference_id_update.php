<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProcedureAppointmentConferenceIdUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $procedure = "DROP PROCEDURE IF EXISTS `appointmentConferenceIdUpdate`;
        CREATE PROCEDURE `appointmentConferenceIdUpdate`()
        BEGIN
        Update `appointments` set conferenceId=Null where id IN (SELECT id FROM `appointments` join durationIntervals on durationIntervals.durationId = appointments.durationId WHERE now() > DATE_ADD(`startDateTime`, INTERVAL durationIntervals.interval MINUTE));
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
         Schema::dropIfExists('appointmentConferenceIdUpdate');
    }
}
