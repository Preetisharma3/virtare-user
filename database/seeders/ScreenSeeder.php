<?php

namespace Database\Seeders;

use App\Models\Screen\Screen;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class ScreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       Screen::truncate();

       $json = File::get("database/data/screen.json");
       $screens = json_decode($json);

       foreach ($screens->screens as $key => $value) {

                Screen::create([
                    'moduleId' => $value->moduleId,
                    'name' => $value->name,
                ]);
       }

    }
}
