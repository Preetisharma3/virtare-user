<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            $procedure = "DROP PROCEDURE IF EXISTS `getCommunicationsByProviderId`;
            CREATE PROCEDURE `getCommunicationsByProviderId` (IN idx int)
            BEGIN
            SELECT * FROM communications WHERE providerId = idx;
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
        // Schema::dropIfExists('communications_procedure');
    }
}
