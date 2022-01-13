<?php

namespace Database\Seeders;

use App\Models\Role\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'roles'=>'SuperAdmin',
            'roleDescription'=>'Has All the access of the application',
            'roleType'=>'Superadmin',
            'masterLogin'=>1
        ]);

        Role::create([
            'roles'=>'Admin',
            'roleDescription'=>'Has All the access of the Non admin section',
            'roleType'=>'Admin',
            'masterLogin'=>0



        ]);

        Role::create([
            'roles'=>'Staff',
            'roleDescription'=>'Only Has the access of the Modules assigned by admin',
            'roleType'=>'Staff',
            'masterLogin'=>0



        ]);

        Role::create([
            'roles'=>'Patient',
            'roleDescription'=>'Only Has the access of the Modules assigned by admin',
            'roleType'=>'Patient',
            'masterLogin'=>0



        ]);
        
    }
}
