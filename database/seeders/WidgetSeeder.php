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

        Widget::create([
            'id'=>'2',
            'name'=>'Today Appointment',
        ]);

        Widget::create([
            'id'=>'3',
            'name'=>'Call Queue',
        ]);

        Widget::create([
            'id'=>'4',
            'name'=>'Patients Stats',
        ]);

        Widget::create([
            'id'=>'5',
            'name'=>'Care Coordinator',
        ]);

        Widget::create([
            'id'=>'6',
            'name'=>'Cpt Code',
        ]);

        Widget::create([
            'id'=>'7',
            'name'=>'Financial Stats',
        ]);

        Widget::create([
            'id'=>'8',
            'name'=>'New Patients Chart',
        ]);

        Widget::create([
            'id'=>'9',
            'name'=>'New Appointment Chart',
        ]);


       
        
    }
}
