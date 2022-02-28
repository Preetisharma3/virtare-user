<?php

namespace Database\Seeders;

use App\Models\Widget\Widget;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Widget::create([
            'id'=>'1',
            'name'=>'Patients widgets',
        ]);

       
        
    }
}
