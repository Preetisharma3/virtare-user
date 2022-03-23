<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddTagsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $createAddTags = "DROP PROCEDURE IF EXISTS `createAddTags`;";

        DB::unprepared($createAddTags);

        $createAddTags = "
           CREATE PROCEDURE  createAddTags(IN data JSON) 
            BEGIN
            INSERT INTO tags (udid,tag,documentId,createdBy) 
            values
             (JSON_UNQUOTE(JSON_EXTRACT(data, '$.udid')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.tag')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.documentId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.createdBy')));
            END;";
  
        DB::unprepared($createAddTags);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_add_tags_procedure');
    }
}
