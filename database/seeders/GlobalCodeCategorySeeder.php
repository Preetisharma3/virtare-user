<?php

namespace Database\Seeders;

use App\Models\GlobalCode\GlobalCodeCategory;
use Illuminate\Database\Seeder;

use function Ramsey\Uuid\v1;

class GlobalCodeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GlobalCodeCategory::create([
            'name'=>'Appointment Type',
            
        ]);
        GlobalCodeCategory::create([
            'name'=>'Specialization'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Communication Category'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Communication status'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Task Status'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Task Category'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Task Priority'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Relationship'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Gender'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Network'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Document Types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Document Tags'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Language'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Contact Type'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Contact Time'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Health Conditions'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Designations'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Insurance Name'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Insurance Type'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Country'
        ]);
        GlobalCodeCategory::create([
            'name'=>'States'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Device Type'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Cities'
        ]);
        GlobalCodeCategory::create([
            'name'=>'MessageCategory'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Email Domains'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Program types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Patient Time Logs Category'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Notification Type'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Notification Status'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Service Type'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Duration'
        ]);
        GlobalCodeCategory::create([
            'name'=>'ScreenType'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Inventory Types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'program durations'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Note Types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Vital Types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'Tracking Types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'widgets types'
        ]);
        GlobalCodeCategory::create([
            'name'=>'custom field type'
        ]);
        GlobalCodeCategory::create([
            'name'=>'entity Type'
        ]);
    }
}
