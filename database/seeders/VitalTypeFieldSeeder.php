<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vital\VitalTypeField;

class VitalTypeFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VitalTypeField::create([
            'vitalTypeId'=>'99',
            'vitalFieldId'=>'1',
            'isFillable'=>1
        ]);
        VitalTypeField::create([
            'vitalTypeId'=>'99',
            'vitalFieldId'=>'2',
            'isFillable'=>1
        ]);
        VitalTypeField::create([
            'vitalTypeId'=>'99',
            'vitalFieldId'=>'3',
            'isFillable'=>1
        ]);
        VitalTypeField::create([
            'vitalTypeId'=>'100',
            'vitalFieldId'=>'4',
            'isFillable'=>1
        ]);
        VitalTypeField::create([
            'vitalTypeId'=>'101',
            'vitalFieldId'=>'5',
            'isFillable'=>1
        ]);
        VitalTypeField::create([
            'vitalTypeId'=>'101',
            'vitalFieldId'=>'6',
            'isFillable'=>1
        ]);
    }
}
