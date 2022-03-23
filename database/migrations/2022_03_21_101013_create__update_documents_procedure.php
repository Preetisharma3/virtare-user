<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateDocumentsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $UpdateDocuments =  "DROP PROCEDURE IF EXISTS `UpdateDocuments`;
      CREATE PROCEDURE `UpdateDocuments`( 
                                            IN id int,
                                            IN name VARCHAR(50),
                                            IN filePath Text(255),
                                            IN documentTypeId int,
                                            IN updatedBy int)
      BEGIN
        UPDATE
        documents
                    SET
                        name = name,
                        filePath = filePath,
                        documentTypeId = documentTypeId,
                        updatedBy = updatedBy
                    WHERE
                        documents.id = id;
                    END;";
      DB::unprepared($UpdateDocuments); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_update_documents_procedure');
    }
}
