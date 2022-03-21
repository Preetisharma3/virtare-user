<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GetGenralParameterList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $procedure = "DROP PROCEDURE IF EXISTS `getGenralParameter`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE PROCEDURE `getGenralParameter`(In Idx INT)
        BEGIN
        SELECT generalParameters.udid as id,vitalTypeFields.vitalFieldId as vitalFieldId, generalParameterGroups.name as generalParameterGroup, vitalFields.name as vitalFieldName,generalParameters.highLimit as highLimit,generalParameters.lowLimit as lowLimit FROM `vitalTypeFields` inner join vitalFields on vitalFields.id = vitalTypeFields.vitalFieldId inner join generalParameterGroups on generalParameterGroups.deviceTypeId = vitalTypeFields.vitalTypeId LEFT join generalParameters on generalParameters.vitalFieldId = vitalFields.id where generalParameterGroups.id = Idx;
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
        Schema::dropIfExists('getGenralParameter');
    }
}
