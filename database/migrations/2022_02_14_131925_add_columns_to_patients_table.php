<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->integer('genderId')->nullable();
            $table->integer('otherLanguageId')->nullable();
            $table->integer('languageId')->nullable();
            $table->integer('contactTypeId')->nullable();
            $table->integer('contactTimeId')->nullable();
            $table->integer('countryId')->nullable();
            $table->integer('stateId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('genderId');
            $table->dropColumn('otherLanguageId');
            $table->dropColumn('languageId');
            $table->dropColumn('contactTypeId');
            $table->dropColumn('contactTimeId');
            $table->dropColumn('countryId');
            $table->dropColumn('stateId');
        });
    }
}
