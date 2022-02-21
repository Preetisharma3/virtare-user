<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStaffProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffProviders', function (Blueprint $table) {
            $table->id();
            $table->string('udid');
            $table->bigInteger('providerId')->unsigned()->nullable();
            $table->foreign('providerId')->references('id')->on('providers')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('staffId')->unsigned()->nullable();
            $table->foreign('staffId')->references('id')->on('staffs')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('isActive')->default(1);
            $table->boolean('isDelete')->default(0);
            $table->bigInteger('createdBy')->unsigned()->nullable();
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('updatedBy')->unsigned()->nullable();
            $table->foreign('updatedBy')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('deletedBy')->unsigned()->nullable();
            $table->foreign('deletedBy')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('deletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staffProviders');
    }
}