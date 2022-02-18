<?php

namespace App;

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
    // Auth Routes
    $router->get('userProfile', 'Api\v1\UserController@userProfile');
    $router->post('logout', 'Api\v1\AuthController@logout');
    // Staff Routes   
    $router->get('staff/access', 'Api\v1\AccessRoleController@assignedRoles');
    // team Routes
    $router->get('team/{type}[/{id}]', 'Api\v1\TeamController@team');
    $router->get('team', 'Api\v1\TeamController@all');
    //$router->get('team/{patientId}/{type}[/{id}]', 'Api\v1\TeamController@team');


    // patient Routes
    $router->post('family', 'Api\v1\PatientController@createFamily');
    $router->put('family/{id}', 'Api\v1\PatientController@createFamily');
    // $router->get('patient/{id}/inventory', 'Api\v1\PatientController@listingPatientInventory');
    $router->put('inventory/{id}/link', 'Api\v1\PatientController@inventory');
    $router->post('patient/vital', 'Api\v1\PatientController@createPatientVital');
    $router->get('patient/vital', 'Api\v1\PatientController@listPatientVital');
    $router->get('patient/{id}/vital', 'Api\v1\PatientController@listPatientVital');
    $router->get('patient/vital/{vitalType}', 'Api\v1\PatientController@latest');
    $router->get('patient/vitalNew', 'Api\v1\PatientController@vital');
    $router->post('patient/{id}/device', 'Api\v1\PatientController@createPatientDevice');
    $router->put('patient/{id}/device/{deviceId}', 'Api\v1\PatientController@createPatientDevice');
    $router->get('patient/{id}/device', 'Api\v1\PatientController@listPatientDevice');

    $router->post('{entityType}/{id}/timeLog', 'Api\v1\PatientController@addPatientTimeLog');
    $router->get('{entityType}/{id}/timeLog[/{timelogId}]', 'Api\v1\PatientController@listPatientTimeLog');
    $router->put('{entityType}/{id}/timeLog/{timelogId}', 'Api\v1\PatientController@addPatientTimeLog');
    $router->delete('{entityType}/{id}/timeLog/{timelogId}', 'Api\v1\PatientController@deletePatientTimeLog');
    $router->get('patient/{id}/goal[/{goalId}]', 'Api\v1\PatientGoalController@index');
    $router->get('patient/goal[/{goalId}]', 'Api\v1\PatientGoalController@index');

    $router->post('patient/{id}/flag', 'Api\v1\PatientController@addPatientFlag');
    $router->get('patient/{id}/flag[/{flagId}]', 'Api\v1\PatientController@listPatientFlag');
    $router->get('patient/{id}/task', 'Api\v1\TaskController@listTask');
    $router->post('patient/{id}/staff', 'Api\v1\PatientController@latest');
    $router->post('{entity}/{id}/notes', 'Api\v1\NoteController@addNote');
    $router->get('{entity}/{id}/notes', 'Api\v1\NoteController@listNote');

    $router->post('patient', 'Api\v1\PatientController@createPatient');
    $router->put('patient/{id}', 'Api\v1\PatientController@updatePatient');
    $router->get('patient[/{id}]', 'Api\v1\PatientController@listPatient');
    $router->delete('patient/{id}', 'Api\v1\PatientController@deletePatient');
    $router->post('patient/{id}/condition', 'Api\v1\PatientController@createPatientCondition');
    $router->get('patient/{id}/condition[/{conditionId}]', 'Api\v1\PatientController@listPatientCondition');
    $router->post('staff', 'Api\v1\StaffController@addStaff');
    $router->get('staff', 'Api\v1\StaffController@listStaff');
    $router->put('staff/{id}', 'Api\v1\StaffController@updateStaff');
    $router->post('patient/{id}/referals', 'Api\v1\PatientController@createPatientReferals');
    $router->put('patient/{id}/referals/{referalsId}', 'Api\v1\PatientController@updatePatientReferals');
    $router->get('patient/{id}/referals[/{referalsId}]', 'Api\v1\PatientController@listPatientReferals');
    $router->delete('patient/{id}/referals/{referalsId}', 'Api\v1\PatientController@deletePatientReferals');
    $router->post('patient/{id}/physician', 'Api\v1\PatientController@createPatientPhysician');
    $router->put('patient/{id}/physician/{physicianId}', 'Api\v1\PatientController@updatePatientPhysician');
    $router->get('patient/{id}/physician[/{physicianId}]', 'Api\v1\PatientController@listPatientPhysician');
    $router->delete('patient/{id}/physician/{physicianId}', 'Api\v1\PatientController@deletePatientPhysician');
    $router->post('patient/{id}/program', 'Api\v1\PatientController@createPatientProgram');
    $router->put('patient/{id}/program/{programId}', 'Api\v1\PatientController@createPatientProgram');
    $router->get('patient/{id}/program[/{programId}]', 'Api\v1\PatientController@listPatientProgram');
    $router->delete('patient/{id}/program/{programId}', 'Api\v1\PatientController@deletePatientProgram');
    $router->post('patient/{id}/inventory', 'Api\v1\PatientController@createPatientInventory');
    $router->put('patient/{id}/inventory/{inventoryId}', 'Api\v1\PatientController@updatePatientInventory');
    $router->delete('patient/{id}/inventory/{inventoryId}', 'Api\v1\PatientController@deletePatientInventory');
    $router->get('patient/{id}/inventory[/{inventoryId}]', 'Api\v1\PatientController@listPatientInventory');
    $router->post('patient/{id}/vital', 'Api\v1\PatientController@createPatientVital');
    $router->put('patient/{id}/vital/{vitalId}', 'Api\v1\PatientController@createPatientVital');
    $router->delete('patient/{id}/vital/{vitalId}', 'Api\v1\PatientController@deletePatientVital');
    $router->post('patient/{id}/medicalHistory', 'Api\v1\PatientController@createPatientMedicalHistory');
    $router->put('patient/{id}/medicalHistory/{medicalHistoryId}', 'Api\v1\PatientController@createPatientMedicalHistory');
    $router->get('patient/{id}/medicalHistory[/{medicalHistoryId}]', 'Api\v1\PatientController@listPatientMedicalHistory');
    $router->delete('patient/{id}/medicalHistory/{medicalHistoryId}', 'Api\v1\PatientController@deletePatientMedicalHistory');
    $router->post('patient/{id}/medicalRoutine', 'Api\v1\PatientController@createPatientMedicalRoutine');
    $router->put('patient/{id}/medicalRoutine/{medicalRoutineId}', 'Api\v1\PatientController@createPatientMedicalRoutine');
    $router->get('patient/{id}/medicalRoutine[/{medicalRoutineId}]', 'Api\v1\PatientController@listPatientMedicalRoutine');
    $router->delete('patient/{id}/medicalRoutine/{medicalRoutineId}', 'Api\v1\PatientController@deletePatientMedicalRoutine');
    $router->post('patient/{id}/insurance', 'Api\v1\PatientController@createPatientInsurance');
    $router->put('patient/{id}/insurance[/{insuranceId}]', 'Api\v1\PatientController@createPatientInsurance');
    $router->get('patient/{id}/insurance[/{insuranceId}]', 'Api\v1\PatientController@listPatientInsurance');
    $router->delete('patient/{id}/insurance/{insuranceId}', 'Api\v1\PatientController@deletePatientInsurance');
    $router->post('patient/{id}/device', 'Api\v1\PatientController@createPatientDevice');
    $router->put('patient/{id}/device/{deviceId}', 'Api\v1\PatientController@createPatientDevice');
    $router->get('patient/{id}/device', 'Api\v1\PatientController@listPatientDevice');
    $router->post('patient/{id}/timeLog', 'Api\v1\PatientController@addPatientTimeLog');
    $router->get('patient/{id}/timeLog[/{timelogId}]', 'Api\v1\PatientController@listPatientTimeLog');
    $router->put('patient/{id}/timeLog/{timelogId}', 'Api\v1\PatientController@addPatientTimeLog');
    $router->delete('patient/{id}/timeLog/{timelogId}', 'Api\v1\PatientController@deletePatientTimeLog');
    $router->get('patient/{id}/timeLine', 'Api\v1\PatientController@listPatientTimeline');


    // Patient Staff Routes
    $router->post('patient/{id}/staff', 'Api\v1\PatientStaffController@assignStaff');
    $router->get('patient/{id}/staff[/{StaffId}]', 'Api\v1\PatientStaffController@getAssignStaff');
    $router->put('patient/{id}/staff/{patientStaffId}', 'Api\v1\PatientStaffController@assignStaff');
    $router->delete('patient/{id}/staff', 'Api\v1\PatientStaffController@deleteAssignStaff');


    $router->get('timeLog[/{id}]', 'Api\v1\TimeLogController@listTimeLog');
    $router->put('timeLog/{id}', 'Api\v1\TimeLogController@updateTimeLog');
    $router->delete('timeLog/{id}', 'Api\v1\TimeLogController@deleteTimeLog');

    // appointment Routes
    // $router->get('patient/vital', 'Api\v1\PatientController@listPatientVital');

    // appointment Routes
    $router->get('appointment/new', 'Api\v1\AppointmentController@newAppointments');
    $router->get('appointment/search', 'Api\v1\AppointmentController@appointmentSearch');
    $router->get('appointment/summary', 'Api\v1\TimelineController@appointmentTotal');
    $router->get('appointment/{id}/today', 'Api\v1\AppointmentController@todayAppointment');
    $router->get('appointment/today', 'Api\v1\AppointmentController@todayAppointment');
    $router->get('appointment[/{id}]', 'Api\v1\AppointmentController@appointmentList');
    $router->post('appointment[/{id}]', 'Api\v1\AppointmentController@addAppointment');

    // Communication Routes
    $router->get('communication/count', 'Api\v1\CommunicationController@countCommunication');
    $router->get('communication/search', 'Api\v1\CommunicationController@searchCommunication');
    $router->get('communication/type', 'Api\v1\CommunicationController@messageType');
    $router->post('communication', 'Api\v1\CommunicationController@addCommunication');
    $router->get('communication', 'Api\v1\CommunicationController@getCommunication');

    // Global Codes Routes
    $router->get('globalCodeCategory[/{id}]', 'Api\v1\GlobalCodeController@globalCodeCategory');
    $router->get('globalCode/{id}', 'Api\v1\GlobalCodeController@globalCode');
    $router->post('globalCode', 'Api\v1\GlobalCodeController@createGlobalCode');
    $router->patch('globalCode[/{id}]', 'Api\v1\GlobalCodeController@updateGlobalCode');
    $router->delete('globalCode[/{id}]', 'Api\v1\GlobalCodeController@deleteGlobalCode');

    // Task Routes
    $router->post('task', 'Api\v1\TaskController@addTask');
    $router->get('task', 'Api\v1\TaskController@listTask');
    $router->get('task/priority', 'Api\v1\TaskController@priorityTask');
    $router->get('task/status', 'Api\v1\TaskController@statusTask');
    $router->get('task/staff', 'Api\v1\TaskController@taskPerStaff');
    $router->get('task/category', 'Api\v1\TaskController@taskPerCategory');
    $router->put('task/{id}', 'Api\v1\TaskController@updateTask');
    $router->delete('task/{id}', 'Api\v1\TaskController@deleteTask');
    $router->get('task/{id}', 'Api\v1\TaskController@taskById');


    // Dashboard Routes
    // $router->get('patient/chart', 'Api\v1\TimelineController@patientTotal');
    // $router->get('patient/count', 'Api\v1\DashboardController@patientCount');

    // Inventory Routes
    $router->post('inventory/{id}', 'Api\v1\InventoryController@store');
    $router->get('inventory', 'Api\v1\InventoryController@index');
    $router->put('inventory/{id}', 'Api\v1\InventoryController@update');
    $router->delete('inventory/{id}', 'Api\v1\InventoryController@destroy');
    $router->get('model', 'Api\v1\InventoryController@getModels');


    //Family Member
    $router->get('familyMember/patient[/{id}]', 'Api\v1\FamilyMemberController@listPatient');

    //Push Notification

    $router->get('notification', 'Api\v1\PushNotificationController@notificationShow');

    // Conversation Routes
    $router->get('conversation/list[/{id}]', 'Api\v1\ConversationController@allConversation');
    $router->get('conversation[/{id}]', 'Api\v1\ConversationController@conversation');
    $router->post('send-message[/{id}]', 'Api\v1\ConversationController@conversationMessage');
    $router->get('get-conversation[/{id}]', 'Api\v1\ConversationController@showConversation');
    $router->get('latest-message[/{id}]', 'Api\v1\ConversationController@latestMessage');


    //Contact Us Routes
    $router->post('requestCall', 'Api\v1\ContactController@index');
    $router->post('contactText', 'Api\v1\ContactController@contactMessage');
    $router->post('contactMail', 'Api\v1\ContactController@contactEmail');


    // Note Routes
    $router->post('{entity}/{id}/notes', 'Api\v1\NoteController@addNote');
    $router->get('{entity}/{id}/notes', 'Api\v1\NoteController@listNote');
});

$router->post('screenAction', 'Api\v1\ScreenActionController@creatScreenAction');
$router->get('getScreenAction', 'Api\v1\ScreenActionController@getScreenAction');



$router->get('patient/condition/count', 'Api\v1\DashboardController@patientConditionCount');
// $router->get('patient/abnormal', 'Api\v1\DashboardController@abnormalPatients');
// $router->get('patient/critical', 'Api\v1\DashboardController@criticalPatients');
// $router->get('patient/condition', 'Api\v1\DashboardController@patientCondition');



$router->post('call', 'Api\v1\CommunicationController@addCallRecord');
$router->get('call/status', 'Api\v1\CommunicationController@callStatus');
$router->get('call/staff', 'Api\v1\CommunicationController@callCountPerStaff');



$router->get('widget', 'Api\v1\WidgetController@getWidget');
$router->put('widget/{id}', 'Api\v1\WidgetController@updateWidget');
$router->get('widget/assign', 'Api\v1\WidgetController@getassignedWidget');
$router->get('program', 'Api\v1\ProgramController@listProgram');
$router->get('staff/network', 'Api\v1\DashboardController@staffNetwork');
$router->get('staff/specialization', 'Api\v1\DashboardController@staffSpecialization');


$router->post('{entity}/{id}/document', 'Api\v1\DocumentController@createDocument');
$router->put('{entity}/{id}/document/{documentId}/tag[/{tagId}]', 'Api\v1\DocumentController@createDocument');
$router->get('{entity}/{id}/document[/{documentId}]', 'Api\v1\DocumentController@listDocument');
$router->delete('{entity}/{id}/document/{documentId}', 'Api\v1\DocumentController@deleteDocument');


$router->post('file', 'Api\v1\FileController@createFile');
$router->delete('file', 'Api\v1\FileController@deleteFile');
$router->get('count/patient', 'Api\v1\DashboardController@patientCountMonthly');
$router->get('count/appointment', 'Api\v1\DashboardController@appointmentCountMonthly');
$router->put('profile', 'Api\v1\UserController@profile');

$router->get('field[/{id}]', 'Api\v1\VitalController@listVitalTypeField');
$router->post('callRecord', 'Api\v1\CommunicationController@addCallRecord');
$router->get('inQueue', 'Api\v1\CommunicationController@inQueue');
$router->get('goingOn', 'Api\v1\CommunicationController@goingOn');
$router->get('completed', 'Api\v1\CommunicationController@completed');
$router->get('staffCallCount', 'Api\v1\CommunicationController@callCountPerStaff');

$router->post('module', 'Api\v1\ModuleController@createModule');
$router->get('module', 'Api\v1\ModuleController@getModule');
$router->post('screen', 'Api\v1\ScreenController@createScreen');
$router->get('screen', 'Api\v1\ScreenController@getScreen');

$router->post('staff/{id}/contact', 'Api\v1\StaffController@addStaffContact');
$router->get('staff/{id}/contact', 'Api\v1\StaffController@listStaffContact');
$router->put('staff/{id}/contact/{staffId}', 'Api\v1\StaffController@updateStaffContact');
$router->delete('staff/{id}/contact/{staffId}', 'Api\v1\StaffController@deleteStaffContact');
$router->post('staff/{id}/availability', 'Api\v1\StaffController@addStaffAvailability');
$router->get('staff/{id}/availability', 'Api\v1\StaffController@listStaffAvailability');
$router->put('staff/{id}/availability/{staffId}', 'Api\v1\StaffController@updateStaffAvailability');
$router->delete('staff/{id}/availability/{staffId}', 'Api\v1\StaffController@deleteStaffAvailability');
$router->post('staff/{id}/role', 'Api\v1\StaffController@addStaffRole');
$router->get('staff/{id}/role', 'Api\v1\StaffController@listStaffRole');
$router->put('staff/{staffId}/role/{id}', 'Api\v1\StaffController@updateStaffRole');
$router->delete('staff/{staffId}/role/{id}', 'Api\v1\StaffController@deleteStaffRole');
$router->post('staff/{id}/provider', 'Api\v1\StaffController@addStaffProvider');
$router->get('staff/{id}/provider', 'Api\v1\StaffController@listStaffProvider');
$router->put('staff/{staffId}/provider/{id}', 'Api\v1\StaffController@updateStaffProvider');
$router->delete('staff/{staffId}/provider/{id}', 'Api\v1\StaffController@deleteStaffProvider');

$router->post('inventory/{id}', 'Api\v1\InventoryController@store');
$router->get('inventory', 'Api\v1\InventoryController@index');
$router->put('inventory/{id}', 'Api\v1\InventoryController@update');
$router->delete('inventory/{id}', 'Api\v1\InventoryController@destroy');
$router->get('model', 'Api\v1\InventoryController@getModels');

$router->get('staff/specialization/count', 'Api\v1\StaffController@specializationCount');
$router->get('staff/network/count', 'Api\v1\StaffController@networkCount');

$router->post('provider', 'Api\v1\ProviderController@store');
$router->post('provider/{id}/location', 'Api\v1\ProviderController@providerLocationStore');
$router->get('provider', 'Api\v1\ProviderController@index');

$router->post('role', 'Api\v1\RolePermissionController@createRole');
$router->get('roleList', 'Api\v1\RolePermissionController@roleList');
$router->get('role/{id}', 'Api\v1\RolePermissionController@editRole');
$router->put('role/{id}', 'Api\v1\RolePermissionController@updateRole');
$router->delete('role/{id}', 'Api\v1\RolePermissionController@deleteRole');
$router->post('rolePermission/{id}', 'Api\v1\RolePermissionController@createRolePermission');
$router->get('permissionList', 'Api\v1\RolePermissionController@permissionsList');
$router->get('rolePermissionList', 'Api\v1\RolePermissionController@rolePermissionList');

$router->get('role', 'Api\v1\AccessRoleController@index');
$router->get('staff/{id}/access', 'Api\v1\AccessRoleController@assignedRoles');

$router->post('generalParameterGroup', 'Api\v1\GeneralParameterController@addGeneralParameterGroup');
$router->get('generalParameterGroup[/{id}]', 'Api\v1\GeneralParameterController@listGeneralParameterGroup');
$router->put('generalParameter/{id}', 'Api\v1\GeneralParameterController@updateGeneralParameter');
$router->delete('generalParameterGroup/{id}', 'Api\v1\GeneralParameterController@deleteGeneralParameterGroup');
$router->delete('generalParameter/{id}', 'Api\v1\GeneralParameterController@deleteGeneralParameter');


// FAQ Routes
$router->get('faq', 'Api\v1\FaqController');
