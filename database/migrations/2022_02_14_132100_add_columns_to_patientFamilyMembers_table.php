<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class AddColumnsToPatientFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patientFamilyMembers', function (Blueprint $table) {
            $table->integer('contactTypeId')->nullable();
            $table->integer('contactTimeId')->nullable();
            $table->integer('genderId')->nullable();
            $table->integer('userId')->nullable();
            $table->integer('relationId')->nullable();
            $table->integer('patientId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patientFamilyMembers', function (Blueprint $table) {
            $table->dropColumn('contactTypeId');
            $table->dropColumn('contactTimeId');
            $table->dropColumn('genderId');
            $table->dropColumn('userId');
            $table->dropColumn('relationId');
            $table->dropColumn('patientId');
        });
    }
}
