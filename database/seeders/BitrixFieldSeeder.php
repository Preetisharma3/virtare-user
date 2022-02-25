<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BitrixField\BitrixField;

class BitrixFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        BitrixField::truncate();

        $bitrixObj = [
            "firstName"             =>  "UF_CRM_1626467334808",
            "lastName"              =>  "UF_CRM_1626467359423",
            "phoneNumber"           =>  "UF_CRM_1626467684203",
            "email"                 =>  "UF_CRM_1626467909588",
            "dob"                   =>  "UF_CRM_1626467308183"
        ];

        foreach($bitrixObj as $K => $val){
            BitrixField::create([
	            'bitrixId' => $val,
	            'patientId' => $K,
	        ]);
        }

    }
}
