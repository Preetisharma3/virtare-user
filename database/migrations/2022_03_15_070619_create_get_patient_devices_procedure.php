<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetPatientDevicesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userdata =  "DROP PROCEDURE IF EXISTS `getPatientdevices`;
      CREATE PROCEDURE `getPatientdevices`(IN deviceId int)
      BEGIN
      SELECT * FROM patientdevices
      LEFT JOIN devices ON patientdevices.deviceId = devices.id
      WHERE patientdevices.udid = deviceId;
      END;";
      DB::unprepared($userdata); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_patient_devices_procedure');
    }
}
