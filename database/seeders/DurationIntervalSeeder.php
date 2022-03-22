<?php

namespace Database\Seeders;

use App\Models\GlobalCode\DurationInterval;
use Illuminate\Database\Seeder;

class DurationIntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DurationInterval::create([
            'durationId' =>'52',
            'minutes'=>'10',
        ]);
        
        DurationInterval::create([
            'durationId' =>'53',
            'minutes'=>'20',
        ]);

        DurationInterval::create([
            'durationId' =>'54',
            'minutes'=>'30',
        ]);

        DurationInterval::create([
            'durationId' =>'55',
            'minutes'=>'40',
        ]);

        DurationInterval::create([
            'durationId' =>'56',
            'minutes'=>'60',
        ]);

        DurationInterval::create([
            'durationId' =>'57',
            'minutes'=>'120',
        ]);

        DurationInterval::create([
            'durationId' =>'58',
            'minutes'=>'180',
        ]);

        DurationInterval::create([
            'durationId' =>'59',
            'minutes'=>'240',
        ]);

        DurationInterval::create([
            'durationId' =>'60',
            'minutes'=>'1440',
        ]);
    }
}
