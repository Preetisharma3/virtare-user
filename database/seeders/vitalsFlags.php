<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vital\VitalFlags;
class vitalsFlags extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         VitalFlags::truncate();

        $flags = [
                    [
                        "name"             =>  "Low",
                        "color"              =>  "#FFB21E",
                        "icon"           =>  "public/assests/images/vitalFlag/low.png"
                    ],
                    [
                        "name"             =>  "Normal",
                        "color"              =>  "#00B755",
                        "icon"           =>  "public/assests/images/vitalFlag/normal.png"
                    ],
                    [
                        "name"             =>  "High",
                        "color"              =>  "#F13535",
                        "icon"           =>  "public/assests/images/vitalFlag/high.png"
                    ],  
                ];

        foreach($flags as $K => $val){
            VitalFlags::create([
                'name' => $val['name'],
                'color' => $val['color'],
                'icon' => $val['icon'],
            ]);
        }
    }
}
