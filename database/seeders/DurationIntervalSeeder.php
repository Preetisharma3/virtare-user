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
            'minutes'=>'1 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'57',
            'minutes'=>'2 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'58',
            'minutes'=>'3 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'59',
            'minutes'=>'4 HOUR',
        ]);

        DurationInterval::create([
            'durationId' =>'60',
            'minutes'=>'24 HOUR',
        ]);
    }
}
