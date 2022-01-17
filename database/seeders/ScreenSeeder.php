<?php

namespace Database\Seeders;

use App\Models\Screen\Screen;
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

        Screen::create([
            'moduleId' => 1,
            'name' => 'Roles & Permissions List',
        ]);

        Screen::create([
            'moduleId' => 2,
            'name' => 'Global Codes List',
        ]);

        Screen::create([
            'moduleId' => 3,
            'name' => 'CPT Codes List',
        ]);


        Screen::create([
            'moduleId' => 5,
            'name' => 'Programs List',
        ]);

        Screen::create([
            'moduleId' => 6,
            'name' => 'Providers List',
        ]);

        Screen::create([
            'moduleId' => 6,
            'name' => 'Provider Summary',
        ]);

        Screen::create([
            'moduleId' => 7,
            'name' => 'Download Report'
        ]);

        Screen::create([
            'moduleId' => 8,
            'name' => 'Care Coordinators List',
        ]);

        Screen::create([
            'moduleId' => 8,
            'name' => 'Care Coordinator Summary',
        ]);

        Screen::create([
            'moduleId' => 9,
            'name' => 'Patients List',
        ]);

        Screen::create([
            'moduleId' => 9,
            'name' => 'Patient Summary',
        ]);

        Screen::create([
            'moduleId' => 11,
            'name' => 'Communications Dashboard View',
        ]);

        Screen::create([
            'moduleId' => 11,
            'name' => 'Communications List View',
        ]);

        Screen::create([
            'moduleId' => 12,
            'name' => 'Appointment Calendar',
        ]);

        Screen::create([
            'moduleId' => 13,
            'name' => 'Tasks Dashboard View',
        ]);

        Screen::create([
            'moduleId' => 13,
            'name' => 'Tasks List View',
        ]);

        Screen::create([
            'moduleId' => 14,
            'name' => 'General parameters',
        ]);

        Screen::create([
            'moduleId' => 15,
            'name' => 'Audit Time Log',
        ]);
    }
}
