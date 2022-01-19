<?php

namespace Database\Seeders;

use App\Models\Action\Action;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Action::truncate();

         $json = File::get("database/data/action.json");
         $actions = json_decode($json);

         foreach ($actions->actions as $key => $value) {

            Action::create([
               'screenId' => $value->screenId,
               'name' => $value->name,
               'controller' => $value->controller,
               'function' => $value->function
            ]);
        }

    }
}
