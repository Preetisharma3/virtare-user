<?php

namespace Database\Seeders;

use App\Models\Action\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Action::create([
            'screenId' => 1,
            'name' => 'Add Role',
            'controller' => 'RollController',
            'function' => '',
        ]);

        Action::create([
            'screenId' => 1,
            'name' => 'Edit Role',
            'controller' => 'RollController',
            'function' => '',
        ]);

        Action::create([
            'screenId' => 1,
            'name' => 'Delete Role',
            'controller' => 'RollController',
            'function' => '',
        ]);

        Action::create([
            'screenId' => 1,
            'name' => 'Active/Inactive Role',
            'controller' => 'RollController',
            'function' => '',
        ]);

        Action::create([
            'screenId' => 1,
            'name' => 'Export Roles',
            'controller' => 'RollController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 2,
            'name' => 'Add Global Codes',
            'controller' => 'GlobalCodeController',
            'function' => 'createGlobalCode',
        ]);

        Action::create([

            'screenId' => 2,
            'name' => 'Update Global Codes',
            'controller' => 'GlobalCodeController',
            'function' => 'updateGlobalCode',
        ]);

        Action::create([

            'screenId' => 2,
            'name' => 'Delete Global Codes',
            'controller' => 'GlobalCodeController',
            'function' => 'deleteGlobalCode',
        ]);

        Action::create([

            'screenId' => 3,
            'name' => 'Add CPT Code',
            'controller' => 'CPTCodeController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 3,
            'name' => 'Edit CPT Code',
            'controller' => 'CPTCodeController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 3,
            'name' => 'Delete CPT Code',
            'controller' => 'CPTCodeController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 3,
            'name' => 'Active/Inactive CPT Code',
            'controller' => 'CPTCodeController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 3,
            'name' => 'Export CPT Code',
            'controller' => 'CPTCodeController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 3,
            'name' => 'Search CPT Codes',
            'controller' => 'CPTCodeController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 4,
            'name' => 'Add Program',
            'controller' => 'ProgramController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 4,
            'name' => 'Edit Program',
            'controller' => 'ProgramController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 4,
            'name' => 'Delete Program',
            'controller' => 'ProgramController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 4,
            'name' => 'Active/Inactive Program',
            'controller' => 'ProgramController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 4,
            'name' => 'Export Program',
            'controller' => 'ProgramController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 4,
            'name' => 'Search Program',
            'controller' => 'ProgramController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Search Providers',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Add Provider',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Edit Provider',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Delete Provider',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Active/Inactive Provider',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Export Provider',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Provider Appointments List',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Provider Patients List',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Provider Permissions List',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 5,
            'name' => 'Provider Documents List',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 6,
            'name' => 'Provider Locations List',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 6,
            'name' => 'Add Provider Location',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 6,
            'name' => 'Edit Provider Location',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 6,
            'name' => 'Delete Provider Location',
            'controller' => 'ProviderController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 7,
            'name' => 'Search Report',
            'controller' => 'ReportController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 7,
            'name' => 'Download Report',
            'controller' => 'ReportController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 8,
            'name' => 'Add Care Coordinator',
            'controller' => 'CareCoordinatorController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 8,
            'name' => 'Edit Care Coordinator',
            'controller' => 'CareCoordinatorController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 8,
            'name' => 'Delete Care Coordinator',
            'controller' => 'CareCoordinatorController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 8,
            'name' => 'View Care Coordinator',
            'controller' => 'CareCoordinatorController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 8,
            'name' => 'Export Care Coordinators',
            'controller' => 'CareCoordinatorController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Appointments List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Patients List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Permissions List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Documents List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Contacts List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Add Care Coordinator Contact',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Edit Care Coordinator Contact',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Delete Care Coordinator Contact',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Availability List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Add Care Coordinator Availability',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Edit Care Coordinator Availability',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Delete Care Coordinator Availability',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Roles List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Add Care Coordinator Role',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Edit Care Coordinator Role',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Delete Care Coordinator Role',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Care Coordinator Documents List',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Add Care Coordinator Document',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Edit Care Coordinator Document',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 9,
            'name' => 'Delete Care Coordinator Document',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Edit Patient',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Delete Patient',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'View Patient',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Export Patients',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Demographics',
            'controller' => 'PatientController',
            'function' => 'createPatient',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Conditions',
            'controller' => 'PatientController',
            'function' => 'createPatientCondition',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Programs',
            'controller' => 'PatientController',
            'function' => 'createPatientProgram',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Edit Patient Program',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Device',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Active/Inactive Patient Device',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Delete Patient Device',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Parameters',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Medical History',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Edit Patient Medical History',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Delete Patient Medical History',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Medication',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Edit Patient Medication',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Delete Patient Medication',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Insurance',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Add Patient Document',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Edit Patient Document',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 10,
            'name' => 'Delete Patient Document',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Notifications List',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Visits List',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Notes List',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Appointments List',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Documents List',
            'controller' => 'PatientController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Patient Time Tracked',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Appointment',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Vital Summary',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Vitals',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'View Vitals',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Notes',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Notes Detail',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Document Detail',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Time Logs Detail',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Edit Patient Time Logs',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Delete Patient Time Logs',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Patient Device Detail',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Edit Patient Device',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Documents',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Care Team',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add TimeLogs',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 11,
            'name' => 'Add Devices',
            'controller' => '',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 12,
            'name' => 'Start Call',
            'controller' => 'CommunicationController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 12,
            'name' => 'End Call',
            'controller' => 'CommunicationController',
            'function' => '',
        ]);


        Action::create([

            'screenId' => 12,
            'name' => 'Send Message',
            'controller' => 'CommunicationController',
            'function' => '',
        ]);


        Action::create([

            'screenId' => 12,
            'name' => 'Export Communications',
            'controller' => 'CommunicationController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 12,
            'name' => 'Add Notes',
            'controller' => 'CommunicationController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 14,
            'name' => 'Add Appointment',
            'controller' => 'AppointmentController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 15,
            'name' => 'Add Task',
            'controller' => 'TaskController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 15,
            'name' => 'Filter Tasks',
            'controller' => 'TaskController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 15,
            'name' => 'Edit Task',
            'controller' => 'TaskController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 15,
            'name' => 'Delete Task',
            'controller' => 'TaskController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 15,
            'name' => 'Active/Inactive Task',
            'controller' => 'TaskController',
            'function' => '',
        ]);

        Action::create([

            'screenId' => 15,
            'name' => 'Export Tasks',
            'controller' => 'TaskController',
            'function' => '',
        ]);
    }
}
