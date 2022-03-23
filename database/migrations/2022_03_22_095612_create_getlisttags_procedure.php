<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetlisttagsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $procedure = "DROP PROCEDURE IF EXISTS `getlistTags`;
        CREATE PROCEDURE `getlistTags`(IN documentIdx INT)
        BEGIN
        SELECT * ,
            tags.documentId as documentTypeId,
            tags.udid as udid
            FROM tags
            JOIN documents ON tags.documentId = documents.id
            WHERE tags.documentId = documentIdx;
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
        Schema::dropIfExists('getlisttags_procedure');
    }
}
