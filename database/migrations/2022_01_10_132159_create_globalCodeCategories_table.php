<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalCodeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globalCodeCategories', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->boolean('isActive')->default(1);
            $table->boolean('isDelete')->default(0);
            $table->bigInteger('createdBy')->unsigned()->nullable();
            $table->bigInteger('updatedBy')->unsigned()->nullable();
            $table->bigInteger('deletedBy')->unsigned()->nullable();
            $table->foreign('createdBy')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updatedBy')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('deletedBy')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

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
        Schema::dropIfExists('global_code_categories');
    }
}
