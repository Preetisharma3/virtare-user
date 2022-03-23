<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPatientDevicesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $createAddPatientDevices = "DROP PROCEDURE IF EXISTS `createAddPatientDevices`;";

        DB::unprepared($createAddPatientDevices);

        $createAddPatientDevices = "
           CREATE PROCEDURE  createAddPatientDevices(IN data JSON) 
            BEGIN
            INSERT INTO patientdevices (udid,patientId,otherDevicesId,status,createdBy) 
            values
             (JSON_UNQUOTE(JSON_EXTRACT(data, '$.udid')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.patientId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.otherDevicesId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.createdBy')));
            END;";
  
        DB::unprepared($createAddPatientDevices);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_add_patient_devices_procedure');
    }
}
