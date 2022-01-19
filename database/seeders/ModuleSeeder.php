<?php

namespace Database\Seeders;

use App\Models\Module\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       Module::truncate();

       $json = File::get("database/data/module.json");
       $modules = json_decode($json);

       foreach ($modules->modules as $key => $value) {

              Module::create([
                     'name' => $value->name,
                     'description'=>$value->description,
              ]);
       }

    }
}
