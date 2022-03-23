<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class globalStartDateEndDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $udid = Str::uuid()->toString();
        $insertObj = array([
            'udid' => Str::uuid()->toString(),
            'globalCodeId' => '122',
            'intervalType' => 'Day',
            'conditions' => '-',
            'number' => '1',
            'isActive' => '1',
        ],
        [
            'udid' => Str::uuid()->toString(),
            'globalCodeId' => '123',
            'intervalType' => 'Week',
            'conditions' => '-',
            'number' => '1',
            'isActive' => '1',
        ],
        [
            'udid' => Str::uuid()->toString(),
            'globalCodeId' => '124',
            'intervalType' => 'Month',
            'conditions' => '-',
            'number' => '1',
            'isActive' => '1',
        ],
        [
            'udid' => Str::uuid()->toString(),
            'globalCodeId' => '125',
            'intervalType' => 'Year',
            'conditions' => '-',
            'number' => '1',
            'isActive' => '1',
        ]
        );
        DB::table("globalstartenddate")->insert($insertObj);
    }
}
