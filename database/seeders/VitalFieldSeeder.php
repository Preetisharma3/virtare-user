<?php

namespace Database\Seeders;

use App\Models\Vital\VitalField;
use Illuminate\Database\Seeder;

class VitalFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VitalField::create([
            'name'=>'Systolic',
        ]);
        VitalField::create([
            'name'=>'Diastolic',
        ]);
        VitalField::create([
            'name'=>'BPM',
        ]);
        VitalField::create([
            'name'=>'SPO2',
        ]);
        VitalField::create([
            'name'=>'Fasting Blood Sugar',
        ]);
        VitalField::create([
            'name'=>'Random Blood Sugar',
        ]);
    }
}
