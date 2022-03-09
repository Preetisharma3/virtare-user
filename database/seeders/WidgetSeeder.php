<?php

namespace Database\Seeders;

use App\Models\Widget\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Widget::create([
            'widgetName' =>'Patients widgets',
            'title'=>'Patients widgets',
        ]);

        Widget::create([
            'widgetName' => 'Today Appointment',
            'title'=>'Today Appointment',
        ]);

        Widget::create([
            'widgetName' => 'Call Queue',
            'title'=>'Call Queue',
        ]);

        Widget::create([
            'widgetName'=> 'Patients Stats',
            'title'=>'Patients Stats',
        ]);

        Widget::create([
            'widgetName' => 'Care Coordinator',
            'title'=>'Care Coordinator',
        ]);

        Widget::create([
            'widgetName'=> 'Cpt Code',
            'title'=>'Cpt Code',
        ]);

        Widget::create([
            'widgetName' => 'Financial Stats',
            'title'=>'Financial Stats',
        ]);

        Widget::create([
            'widgetName' => 'New Patients Chart',
            'title'=>'New Patients Chart',
        ]);

        Widget::create([
            'widgetName' => 'New Appointment Chart',
            'title'=>'New Appointment Chart',
        ]);


       
        
    }
}
