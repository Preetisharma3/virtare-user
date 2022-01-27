<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateScreenActionProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $screenAction = "DROP PROCEDURE IF EXISTS `createScreenAction`;
            CREATE PROCEDURE  createScreenAction(IN userId int, IN actionId int,IN deviceId int) 
            BEGIN
            INSERT INTO screen_actions (userId,actionId,deviceId) values(userId,actionId,deviceId);
            END;";
  
        DB::unprepared($screenAction);

        // $getScreenAction = "DROP PROCEDURE IF EXISTS `getScreenAction`;
        //     CREATE PROCEDURE `getScreenAction` (IN idx int)
        //     BEGIN
        //     SELECT actions.name,users.email,screen_actions.createdAt FROM actions 
        //     LEFT JOIN screen_actions ON actions.id = screen_actions.actionId 
        //     LEFT JOIN users ON users.id = screen_actions.userId WHERE userId=idx;
        //     END; ";
           
        // DB::unprepared($getScreenAction);   
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('screen_action_procedure');
    }
}
