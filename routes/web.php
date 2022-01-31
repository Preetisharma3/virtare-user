<?php

use App\Models\Access\Access;
use Illuminate\Support\Facades\DB;
use App\Transformers\Patient\PatientTransformer;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/linkstorage', function () use ($router) {

    $public = getcwd();
    $storage = dirname(getcwd()) . "/storage";

    $command = 'ln -s ' . $storage . ' ' . $public;

    system($command);
});
$router->post('login', 'Api\v1\AuthController@login');
$router->post('refreshToken', 'Api\v1\AuthController@refreshToken');
$router->group(['middleware' => 'auth:api'], function () use ($router) {
    $router->get('userProfile', 'Api\v1\UserController@userProfile');
    $router->post('logout', 'Api\v1\AuthController@logout');
    $router->post('appointment', 'Api\v1\AppointmentController@addAppointment');
    $router->get('appointment', 'Api\v1\AppointmentController@appointmentList');
   
    $router->get('team', 'Api\v1\TeamController@all');
    $router->get('team/{type}[/{id}]', 'Api\v1\TeamController@team');
});
$router->get('appointment/today', 'Api\v1\AppointmentController@todayAppointment');
$router->post('screenAction', 'Api\v1\ScreenActionController@creatScreenAction');
$router->get('getScreenAction', 'Api\v1\ScreenActionController@getScreenAction');
$router->get('communication/count', 'Api\v1\CommunicationController@countCommunication');
$router->get('communication/search', 'Api\v1\CommunicationController@searchCommunication');
$router->get('communication/type', 'Api\v1\CommunicationController@messageType');
$router->post('communication', 'Api\v1\CommunicationController@addCommunication');
$router->get('communication', 'Api\v1\CommunicationController@getCommunication');
$router->get('globalCodeCategory', 'Api\v1\GlobalCodeController@globalCodeCategory');
$router->post('globalCode', 'Api\v1\GlobalCodeController@createGlobalCode');
$router->put('globalCode[/{id}]', 'Api\v1\GlobalCodeController@updateGlobalCode');
$router->delete('globalCode[/{id}]', 'Api\v1\GlobalCodeController@deleteGlobalCode');
$router->get('patient/chart', 'Api\v1\TimelineController@patientTotal');
$router->get('appointment/summary', 'Api\v1\TimelineController@appointmentTotal');
$router->get('patient/count', 'Api\v1\DashboardController@patientCount');
$router->get('patient/condition/count', 'Api\v1\DashboardController@patientConditionCount');
$router->get('patient/abnormal', 'Api\v1\DashboardController@abnormalPatients');
$router->get('patient/critical', 'Api\v1\DashboardController@criticalPatients');
$router->get('patient/condition', 'Api\v1\DashboardController@patientCondition');
$router->post('patient', 'Api\v1\PatientController@createPatient');
$router->get('patient[/{id}]', 'Api\v1\PatientController@listPatient');
$router->post('patient/{id}/condition', 'Api\v1\PatientController@createPatientCondition');
$router->get('patient/{id}/condition[/{conditionId}]', 'Api\v1\PatientController@listPatientCondition');
$router->post('staff', 'Api\v1\StaffController@addStaff');
$router->get('staff', 'Api\v1\StaffController@listStaff');
$router->post('patient/{id}/referals', 'Api\v1\PatientController@createPatientReferals');
$router->get('patient/{id}/referals[/{referalsId}]', 'Api\v1\PatientController@listPatientReferals');
$router->post('patient/{id}/physician', 'Api\v1\PatientController@createPatientPhysician');
$router->get('patient/{id}/physician[/{physicianId}]', 'Api\v1\PatientController@listPatientPhysician');
$router->post('patient/{id}/program', 'Api\v1\PatientController@createPatientProgram');
$router->get('patient/{id}/program[/{programId}]', 'Api\v1\PatientController@listPatientProgram');
$router->post('patient/{id}/inventory', 'Api\v1\PatientController@createPatientInventory');
$router->get('patient/{id}/inventory[/{inventoryId}]', 'Api\v1\PatientController@listPatientInventory');
$router->post('patient/{id}/vital', 'Api\v1\PatientController@createPatientVital');
$router->get('patient/{id}/vital[/{vitalId}]', 'Api\v1\PatientController@listPatientVital');
$router->post('patient/{id}/medicalHistory', 'Api\v1\PatientController@createPatientMedicalHistory');
$router->get('patient/{id}/medicalHistory[/{medicalHistoryId}]', 'Api\v1\PatientController@listPatientMedicalHistory');
$router->post('patient/{id}/medicalRoutine', 'Api\v1\PatientController@createPatientMedicalRoutine');
$router->get('patient/{id}/medicalRoutine[/{medicalRoutineId}]', 'Api\v1\PatientController@listPatientMedicalRoutine');
$router->post('patient/{id}/insurance', 'Api\v1\PatientController@createPatientInsurance');
$router->get('patient/{id}/insurance[/{insuranceId}]', 'Api\v1\PatientController@listPatientInsurance');
$router->post('call', 'Api\v1\CommunicationController@addCallRecord');
$router->get('call/status', 'Api\v1\CommunicationController@callStatus');
$router->get('call/staff', 'Api\v1\CommunicationController@callCountPerStaff');
$router->get('appointment/future', 'Api\v1\AppointmentController@futureAppointment');
$router->get('appointment/new', 'Api\v1\AppointmentController@newAppointments');
$router->post('task', 'Api\v1\TaskController@addTask');
$router->get('task', 'Api\v1\TaskController@listTask');
$router->get('task/priority', 'Api\v1\TaskController@priorityTask');
$router->get('task/status', 'Api\v1\TaskController@statusTask');
$router->get('widget', 'Api\v1\WidgetController@getWidget');
$router->put('widget/{id}', 'Api\v1\WidgetController@updateWidget');
$router->get('widget/assign', 'Api\v1\WidgetController@getassignedWidget');
$router->get('program', 'Api\v1\ProgramController@listProgram');
$router->get('staff/network', 'Api\v1\DashboardController@staffNetwork');
$router->get('staff/specialization', 'Api\v1\DashboardController@staffSpecialization');


$router->post('{entity}/{id}/document', 'Api\v1\DocumentController@createDocument');
$router->get('{entity}/{id}/document[/{documentId}]', 'Api\v1\DocumentController@listDocument');


$router->post('file', 'Api\v1\FileController@createFile');
$router->delete('file', 'Api\v1\FileController@deleteFile');
$router->get('count/patient','Api\v1\DashboardController@patientCountMonthly');
$router->get('count/appointment','Api\v1\DashboardController@appointmentCountMonthly');
$router->put('profile','Api\v1\UserController@profile');
$router->get('field[/{id}]','Api\v1\VitalController@listVitalTypeField');
$router->post('callRecord', 'Api\v1\CommunicationController@addCallRecord');
$router->get('inQueue','Api\v1\CommunicationController@inQueue');
$router->get('goingOn','Api\v1\CommunicationController@goingOn');
$router->get('completed','Api\v1\CommunicationController@completed');
$router->get('staffCallCount','Api\v1\CommunicationController@callCountPerStaff');
$router->get('futureAppointment', 'Api\v1\AppointmentController@futureAppointment');
$router->get('newAppointment', 'Api\v1\AppointmentController@newAppointments');
$router->get('todayAppointment', 'Api\v1\AppointmentController@todayAppointment');
$router->get('patientList', 'Api\v1\PatientController@listPatient');
$router->post('patientReferals/{id}', 'Api\v1\PatientController@createPatientReferals');
$router->post('patientPhysician/{id}', 'Api\v1\PatientController@createPatientPhysician');
$router->post('patientProgram/{id}', 'Api\v1\PatientController@createPatientProgram');
$router->post('patientVital/{id}', 'Api\v1\PatientController@createPatientVital');
$router->post('module', 'Api\v1\ModuleController@createModule');
$router->get('module', 'Api\v1\ModuleController@getModule');
$router->post('screen', 'Api\v1\ScreenController@createScreen');
$router->get('screen', 'Api\v1\ScreenController@getScreen');

