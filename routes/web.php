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
    $router->post('communication', 'Api\v1\CommunicationController@addCommunication');
    $router->get('communication', 'Api\v1\CommunicationController@getCommunication');
});

$router->get('globalCodeCategory', 'Api\v1\GlobalCodeController@globalCodeCategory');
$router->post('globalCode', 'Api\v1\GlobalCodeController@createGlobalCode');
$router->put('globalCode/{id}', 'Api\v1\GlobalCodeController@updateGlobalCode');
$router->delete('globalCode/{id}', 'Api\v1\GlobalCodeController@deleteGlobalCode');

$router->post('patient', 'Api\v1\PatientController@createPatient');
$router->get('patientList', 'Api\v1\PatientController@listPatient');
$router->post('patientCondition/{id}', 'Api\v1\PatientController@createPatientCondition');
$router->post('patientReferals/{id}', 'Api\v1\PatientController@createPatientReferals');
$router->post('patientPhysician/{id}', 'Api\v1\PatientController@createPatientPhysician');
$router->post('patientProgram/{id}', 'Api\v1\PatientController@createPatientProgram');
$router->post('patientVital/{id}', 'Api\v1\PatientController@createPatientVital');



$router->post('staff', 'Api\v1\StaffController@addStaff');