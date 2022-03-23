<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddDocumentsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createAddDocuments = "DROP PROCEDURE IF EXISTS `createAddDocuments`;";

        DB::unprepared($createAddDocuments);

        $createAddDocuments = "
           CREATE PROCEDURE  createAddDocuments(IN data JSON) 
            BEGIN
            INSERT INTO documents (udid,name,filePath,documentTypeId,referanceId,entityType,createdBy) 
            values
             (JSON_UNQUOTE(JSON_EXTRACT(data, '$.udid')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.name')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.filePath')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.documentTypeId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.referanceId')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.entityType')),JSON_UNQUOTE(JSON_EXTRACT(data, '$.createdBy')));
              SELECT last_insert_id() AS documentId
             ;
            END;";
           
  
        DB::unprepared($createAddDocuments);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_add_documents_procedure');
    }
}
