<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatePatientDevicesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  $userdata =  "DROP PROCEDURE IF EXISTS `updatePatientDevices`;
      CREATE PROCEDURE `updatePatientDevices`( 
                                            IN id int,
                                            IN patientId int,
                                            IN otherDeviceId int,
                                            IN status int,
                                            IN isActive int,
                                            IN updatedBy int)
      BEGIN
        UPDATE
        patientdevices
                    SET
                        patientId = patientId,
                        otherDeviceId = otherDeviceId,
                        status = status,
                        updatedBy = updatedBy
                    WHERE
                        patientdevices.id = id;
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
        Schema::dropIfExists('_update_patient_devices_procedure');
    }
}
