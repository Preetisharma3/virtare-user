<?php

namespace Database\Seeders;

use Illuminate\Support\Str;

use function Ramsey\Uuid\v1;
use Illuminate\Database\Seeder;

use App\Models\Vital\VitalField;
use App\Models\Vital\VitalTypeField;
use App\Models\GlobalCode\GlobalCode;
use App\Models\GlobalCode\GlobalCodeCategory;

class GlobalCodeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $udid = Str::uuid()->toString();


        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Appointment Type',

        ]);
        $specialization=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Specialization'
        ]);

        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $specialization->id,
            'name' => 'Wellness',
            'description' => 'Specialization'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $specialization->id,
            'name' => 'Behaviour',
            'description' => 'Specialization'
        ]);

        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Communication Category'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Communication status'
        ]);
        $taskStatus=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Task Status'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $taskStatus->id,
            'name' => 'Waiting',
            'description' => 'Task Status'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $taskStatus->id,
            'name' => 'In Progress',
            'description' => 'Task Status'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $taskStatus->id,
            'name' => 'Completed',
            'description' => 'Task Status'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Task Category'
        ]);
        $taskPriority=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Task Priority'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $taskPriority->id,
            'name' => 'Urgent',
            'description' => 'Task Priority'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $taskPriority->id,
            'name' => 'Medium',
            'description' => 'Task Priority'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $taskPriority->id,
            'name' => 'Normal',
            'description' => 'Task Priority'
        ]);
        $relationship=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Relationship'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $relationship->id,
            'name' => 'Son',
            'description' => 'Relationship'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $relationship->id,
            'name' => 'Daughter',
            'description' => 'Relationship'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $relationship->id,
            'name' => 'Brother',
            'description' => 'Relationship'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $relationship->id,
            'name' => 'Sister',
            'description' => 'Relationship'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $relationship->id,
            'name' => 'Father',
            'description' => 'Relationship'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $relationship->id,
            'name' => 'Mother',
            'description' => 'Relationship'
        ]);
        $gender=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Gender'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $gender->id,
            'name' => 'Male',
            'description' => 'Gender'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $gender->id,
            'name' => 'Female',
            'description' => 'Gender'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $gender->id,
            'name' => 'Other',
            'description' => 'Gender'
        ]);
        $network=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Network'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $network->id,
            'name' => 'In',
            'description' => 'Network'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $network->id,
            'name' => 'Out',
            'description' => 'Network'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Document Types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Document Tags'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Language'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Contact Type'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Contact Time'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Health Conditions'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Designations'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Insurance Name'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Insurance Type'
        ]);
        $country=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Country'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $country->id,
            'name' => 'United States',
            'description' => 'Network'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'States'
        ]);
        $category = GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Device Type'
        ]);
        $bloodpressure = GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $category->id,
            'name' => 'Blood Pressure',
            'description' => 'Device Type'
        ]);
        $oxymeter = GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $category->id,
            'name' => 'Oxymeter',
            'description' => 'Device Type'
        ]);
        $glucose = GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $category->id,
            'name' => 'Glucose',
            'description' => 'Device Type'
        ]);
        $systolic = VitalField::create([
            'udid' => $udid,
            'name' => 'Systolic',
        ]);
        $diastolic = VitalField::create([
            'udid' => $udid,
            'name' => 'Diastolic',
        ]);
        $bpm = VitalField::create([
            'udid' => $udid,
            'name' => 'BPM',
        ]);
        $spo = VitalField::create([
            'udid' => $udid,
            'name' => 'SPO2',
        ]);
        $fasting = VitalField::create([
            'udid' => $udid,
            'name' => 'Fasting Blood Sugar',
        ]);
        $random = VitalField::create([
            'udid' => $udid,
            'name' => 'Random Blood Sugar',
        ]);
        VitalTypeField::create([
            'udid' => $udid,
            'vitalTypeId' => $bloodpressure->id,
            'vitalFieldId' => $systolic->id,
            'isFillable' => 1
        ]);
        VitalTypeField::create([
            'udid' => $udid,
            'vitalTypeId' => $bloodpressure->id,
            'vitalFieldId' => $diastolic->id,
            'isFillable' => 1
        ]);
        VitalTypeField::create([
            'udid' => $udid,
            'vitalTypeId' => $bloodpressure->id,
            'vitalFieldId' => $bpm->id,
            'isFillable' => 1
        ]);
        VitalTypeField::create([
            'udid' => $udid,
            'vitalTypeId' => $oxymeter->id,
            'vitalFieldId' => $spo->id,
            'isFillable' => 1
        ]);
        VitalTypeField::create([
            'udid' => $udid,
            'vitalTypeId' => $glucose->id,
            'vitalFieldId' => $fasting->id,
            'isFillable' => 1
        ]);
        VitalTypeField::create([
            'udid' => $udid,
            'vitalTypeId' => $glucose->id,
            'vitalFieldId' => $random->id,
            'isFillable' => 1
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'MessageCategory'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Email Domains'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Program types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Patient Time Logs Category'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Notification Type'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Notification Status'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Service Type'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Duration'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'ScreenType'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Inventory Types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'program durations'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Note Types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Vital Types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Tracking Types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'widgets types'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'custom field type'
        ]);
        GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'entity Type'
        ]);
        $timeline=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Timeline'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $timeline->id,
            'name' => 'Day',
            'description' => 'Timeline'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $timeline->id,
            'name' => 'Week',
            'description' => 'Timeline'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $timeline->id,
            'name' => 'Month',
            'description' => 'Timeline'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $timeline->id,
            'name' => 'Year',
            'description' => 'Timeline'
        ]);
        $otherDevices=GlobalCodeCategory::create([
            'udid' => $udid,
            'name' => 'Other Devices'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $otherDevices->id,
            'name' => 'Apple',
            'description' => 'Other Devices'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $otherDevices->id,
            'name' => 'Fitbit',
            'description' => 'Other Devices'
        ]);
        GlobalCode::create([
            'udid' => $udid,
            'globalCodeCategoryId' => $otherDevices->id,
            'name' => 'GoogleFit',
            'description' => 'Other Devices'
        ]);
    }
}
