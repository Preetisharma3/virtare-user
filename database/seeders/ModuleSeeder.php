<?php

namespace Database\Seeders;

use App\Models\Module\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Module::create([
               'name'=>'roles and permissions',
               'description'=>'This module is use to give rolls and permission to the users',
        ]);

        Module::create([
               'name'=>'global codes',
               'description'=>'All the values of keys in global codes are stored.',
        ]);

        Module::create([
               'name' =>'cpt codes',
               'description'=>'CPT codes are the billing codes defined by the American Medical Association',
        ]);

        Module::create([
               'name' =>'documents',
               'description'=>'Documents like id-proof, clinical proof etc are stored',
        ]);

        Module::create([
               'name' =>'programs',
               'description'=>'Programs that a particular provider will provide to its patients.',
        ]);

        Module::create([
               'name' => 'providers',
               'description'=> 'Providers are the facilities that will be using the SAAS based platform that the virtare health  is providing.',
        ]);

        Module::create([
               'name' => 'reports',
               'description'=> 'Different types of reports will be  For Example AuditLog, timeLogs, staffReports, patientReports.',
        ]);

        Module::create([
               'name' => 'staff',
               'description'=> 'Staff of virtare health or staff of provider  we can diffrentiate between them with network which is a global code category',
        ]);

        Module::create([
               'name' => 'patients',
               'description'=> 'Patients demographics that are enrolling under providers to treatment.',
        ]);

        Module::create([
               'name' => 'time-track',
               'description' => 'Keeps the track spend by physician on patient monitoring.',
        ]);

        Module::create([
               'name' => 'communications',
               'description' => 'Contains the information about all the commmunications',
        ]);

        Module::create([
               'name' => 'appointments',
               'description' => 'It includes details of all the appointments of the staffs.',
        ]);

        Module::create([
               'name' => 'tasks',
               'description'=> 'Tasks assigned to staff members.',
        ]);

        Module::create([
               'name' => 'general parameters',
               'description'=> 'general parameters includes all the vitals parameters groups with normal range vitals',
        ]);

        Module::create([
               'name' => 'audit logs',
               'description'=>'Audit log keeps the records of Staff and patient time with each other spend during appointment',
        ]);

        Module::create([
               'name' => 'triggers',
               'description'=> 'It stores all triggers after every action performed',
        ]);
    }
}
