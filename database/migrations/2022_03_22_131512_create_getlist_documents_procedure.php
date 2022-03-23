<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetlistDocumentsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $procedure = "DROP PROCEDURE IF EXISTS `getDocuments`;
        CREATE PROCEDURE `getDocuments`(IN documentIdx INT)
        BEGIN
        SELECT 
           documents.udid AS udid,
           documents.name AS name,
           globalcodes.name AS type,
           patients.referanceId AS patientId,
           documents.filePath AS document,
           patients.documentTypeId AS patientId,
           documents.entityType AS entity

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
        Schema::dropIfExists('getlist_documents_procedure');
    }
}
