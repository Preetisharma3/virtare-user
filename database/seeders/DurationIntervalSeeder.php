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
            'minutes'=>'10 MINUTE',
        ]);
        
        DurationInterval::create([
            'durationId' =>'53',
            'minutes'=>'20 MINUTE',
        ]);

        DurationInterval::create([
            'durationId' =>'54',
            'minutes'=>'30 MINUTE',
        ]);

        DurationInterval::create([
            'durationId' =>'55',
            'minutes'=>'40 MINUTE',
        ]);

        DurationInterval::create([
            'durationId' =>'56',
            'minutes'=>'60 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'57',
            'minutes'=>'120 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'58',
            'minutes'=>'180 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'59',
            'minutes'=>'240 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'60',
            'minutes'=>'1440 HOUR',
        ]);
    }
}
