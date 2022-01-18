<?php

use App\Models\Access\Access;

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
$router->get('userProfile', 'Api\v1\UserController@userProfile');
$router->post('logout', 'Api\v1\AuthController@logout');
$router->group(['middleware' => 'auth:api'], function () use ($router) {
    $router->get('userProfile', 'Api\v1\UserController@userProfile');
    $router->post('logout', 'Api\v1\AuthController@logout');
});
$router->post('communication[/{id}]', 'Api\v1\CommunicationController@addCommunication');
$router->get('communication', 'Api\v1\CommunicationController@getCommunication');
$router->get('globalCodeCategory', 'Api\v1\GlobalCodeController@globalCodeCategory');
$router->post('globalCode', 'Api\v1\GlobalCodeController@createGlobalCode');
$router->put('globalCode[/{id}]', 'Api\v1\GlobalCodeController@updateGlobalCode');
$router->delete('globalCode[/{id}]', 'Api\v1\GlobalCodeController@deleteGlobalCode');
$router->post('patient', 'Api\v1\PatientController@createPatient');
$router->get('patient[/{id}]', 'Api\v1\PatientController@listPatient');
$router->post('patient/{id}/condition', 'Api\v1\PatientController@createPatientCondition');
$router->get('patient/{id}/condition', 'Api\v1\PatientController@listPatientCondition');
$router->post('staff', 'Api\v1\StaffController@addStaff');
$router->post('patient/{id}/referals', 'Api\v1\PatientController@createPatientReferals');
$router->get('patient/{id}/referals', 'Api\v1\PatientController@listPatientReferals');
$router->post('patient/{id}/physician', 'Api\v1\PatientController@createPatientPhysician');
$router->get('patient/{id}/physician', 'Api\v1\PatientController@listPatientPhysician');
$router->post('patient/{id}/program', 'Api\v1\PatientController@createPatientProgram');
$router->get('patient/{id}/program', 'Api\v1\PatientController@listPatientProgram');
$router->post('patient/{id}/inventory','Api\v1\PatientController@createPatientInventory');
$router->get('patient/{id}/inventory','Api\v1\PatientController@listPatientInventory');
$router->post('patient/{id}/vital', 'Api\v1\PatientController@createPatientVital');
$router->get('patient/{id}/vital', 'Api\v1\PatientController@listPatientVital');
$router->post('patient/{id}/medicalHistory', 'Api\v1\PatientController@createPatientMedicalHistory');
$router->get('patient/{id}/medicalHistory', 'Api\v1\PatientController@listPatientMedicalHistory');
$router->post('patient/{id}/medicalRoutine', 'Api\v1\PatientController@createPatientMedicalRoutine');
$router->get('patient/{id}/medicalRoutine', 'Api\v1\PatientController@listPatientMedicalRoutine');
$router->post('call', 'Api\v1\CommunicationController@addCallRecord');
$router->get('call/inQueue','Api\v1\CommunicationController@inQueue');
$router->get('call/goingOn','Api\v1\CommunicationController@goingOn');
$router->get('call/completed','Api\v1\CommunicationController@completed');
$router->get('call/staff','Api\v1\CommunicationController@callCountPerStaff');
$router->get('appointment/future', 'Api\v1\AppointmentController@futureAppointment');
$router->get('appointment/new', 'Api\v1\AppointmentController@newAppointments');
$router->get('appointment/today', 'Api\v1\AppointmentController@todayAppointment');
$router->post('task','Api\v1\TaskController@addTask'); 
$router->get('task','Api\v1\TaskController@listTask');
$router->get('task/priority','Api\v1\TaskController@priorityTask');
$router->get('task/status','Api\v1\TaskController@statusTask');
$router->get('program','Api\v1\ProgramController@listProgram');

