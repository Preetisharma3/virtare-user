<?php

namespace App\Http\Controllers\Api\v1;

use \App\Library\BitrixApi;
use Illuminate\Http\Request;
use App\Services\Api\BitrixService;
use App\Services\Api\FamilyService;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientService;
use App\Http\Requests\Patient\PatientRequest;
use App\Http\Requests\Patient\PatientProgramRequest;
use App\Http\Requests\Patient\PatientReferalRequest;
use App\Http\Requests\Patient\PatientConditionRequest;
use App\Http\Requests\Patient\PatientPhysicianRequest;
use App\Http\Requests\Patient\PatientMedicalHistoryRequest;
use App\Http\Requests\Patient\PatientMedicalRoutineRequest;

class PatientController extends Controller
{

  public function createPatient(PatientRequest $request, $id = null)
  {
    return (new PatientService)->patientCreate($request, $id);
  }

  public function updatePatient(Request $request, $id)
  {
    return (new PatientService)->patientCreate($request, $id);
  }

  public function listPatient(Request $request, $id = null)
  {
    return (new PatientService)->patientList($request, $id);
  }

  public function deletePatient(Request $request, $id)
  {
    return (new PatientService)->patientDelete($request, $id);
  }

  public function createPatientCondition(PatientConditionRequest $request, $id)
  {
    return (new PatientService)->patientConditionCreate($request, $id);
  }

  public function listPatientCondition(Request $request, $id, $conditionId = null)
  {
    return (new PatientService)->patientConditionList($request, $id, $conditionId);
  }

  public function createPatientReferals(PatientReferalRequest $request, $id, $referalsId = null)
  {
    return (new PatientService)->patientReferalsCreate($request, $id, $referalsId);
  }

  public function updatePatientReferals(Request $request, $id, $referalsId = null)
  {
    return (new PatientService)->patientReferalsCreate($request, $id, $referalsId);
  }

  public function listPatientReferals(Request $request, $id, $referalsId = null)
  {
    return (new PatientService)->patientReferalsList($request, $id, $referalsId);
  }

  public function deletePatientReferals(Request $request, $id, $referalsId)
  {
    return (new PatientService)->patientReferalsDelete($request, $id, $referalsId);
  }

  public function createPatientPhysician(PatientPhysicianRequest $request, $id, $physicianId = null)
  {
    return (new PatientService)->patientPhysicianCreate($request, $id, $physicianId);
  }

  public function updatePatientPhysician(Request $request, $id, $physicianId = null)
  {
    return (new PatientService)->patientPhysicianCreate($request, $id, $physicianId);
  }

  public function listPatientPhysician(Request $request, $id, $physicianId = null)
  {
    return (new PatientService)->patientPhysicianList($request, $id, $physicianId);
  }

  public function deletePatientPhysician(Request $request, $id, $physicianId)
  {
    return (new PatientService)->patientPhysicianDelete($request, $id, $physicianId);
  }

  public function createPatientProgram(PatientProgramRequest $request, $id, $programId = null)
  {
    return (new PatientService)->patientProgramCreate($request, $id, $programId);
  }

  public function listPatientProgram(Request $request, $id, $programId = null)
  {
    return (new PatientService)->patientProgramList($request, $id, $programId);
  }

  public function deletePatientProgram(Request $request, $id, $programId = null)
  {
    return (new PatientService)->patientProgramDelete($request, $id, $programId);
  }

  public function createPatientInventory(Request $request, $id, $inventoryId = null)
  {
    return (new PatientService)->patientInventoryCreate($request, $id, $inventoryId);
  }

  public function updatePatientInventory(Request $request, $id, $inventoryId = null)
  {
    return (new PatientService)->patientInventoryCreate($request, $id, $inventoryId);
  }

  public function listPatientInventory(Request $request, $id, $inventoryId = null)
  {
    return (new PatientService)->patientInventoryList($request, $id, $inventoryId);
  }

  public function deletePatientInventory(Request $request, $id, $inventoryId)
  {
    return (new PatientService)->patientInventoryDelete($request, $id, $inventoryId);
  }

  public function createPatientVital(Request $request, $id = null)
  {
    return (new PatientService)->patientVitalCreate($request, $id);
  }

  public function listPatientVital(Request $request, $id = null)
  {
    return (new PatientService)->patientVitalList($request, $id);
  }

  public function vital(Request $request, $id = null)
  {
    return (new PatientService)->vitalList($request, $id);
  }

  public function latest(Request $request, $id = null, $vitalType = null)
  {
    return (new PatientService)->latest($request, $id, $vitalType);
  }

  public function deletePatientVital(Request $request, $id, $vitalId = null)
  {
    return (new PatientService)->patientVitalDelete($request, $id, $vitalId);
  }

  public function createPatientMedicalHistory(PatientMedicalHistoryRequest $request, $id, $medicalHistoryId = null)
  {
    return (new PatientService)->patientMedicalHistoryCreate($request, $id, $medicalHistoryId);
  }

  public function listPatientMedicalHistory(Request $request, $id, $medicalHistoryId = null)
  {
    return (new PatientService)->patientMedicalHistoryList($request, $id, $medicalHistoryId);
  }

  public function deletePatientMedicalHistory(Request $request, $id, $medicalHistoryId)
  {
    return (new PatientService)->patientMedicalHistoryDelete($request, $id, $medicalHistoryId);
  }

  public function createPatientMedicalRoutine(PatientMedicalRoutineRequest $request, $id, $medicalRoutineId = null)
  {
    return (new PatientService)->patientMedicalRoutineCreate($request, $id, $medicalRoutineId);
  }

  public function listPatientMedicalRoutine(Request $request, $id, $medicalRoutineId = null)
  {
    return (new PatientService)->patientMedicalRoutineList($request, $id, $medicalRoutineId);
  }

  public function deletePatientMedicalRoutine(Request $request, $id, $medicalRoutineId)
  {
    return (new PatientService)->patientMedicalRoutineDelete($request, $id, $medicalRoutineId);
  }

  public function createPatientInsurance(Request $request, $id, $insuranceId = null)
  {
    return (new PatientService)->patientInsuranceCreate($request, $id, $insuranceId);
  }

  public function listPatientInsurance(Request $request, $id, $insuranceId = null)
  {
    return (new PatientService)->patientInsuranceList($request, $id, $insuranceId);
  }

  public function deletePatientInsurance(Request $request, $id, $insuranceId)
  {
    return (new PatientService)->patientInsuranceDelete($request, $id, $insuranceId);
  }

  public function listingPatientInventory(Request $request, $id = null)
  {
    return (new PatientService)->patientInventoryListing($request, $id);
  }

  public function inventory(Request $request, $id)
  {
    return (new PatientService)->inventoryUpdate($request, $id);
  }

  public function createPatientDevice(Request $request, $id=null, $deviceId = null)
  {
    return (new PatientService)->patientDeviceCreate($request, $id, $deviceId);
  }

  public function listPatientDevice(Request $request, $id=null, $deviceId = null)
  {
    return (new PatientService)->patientDeviceList($request, $id, $deviceId);
  }

  public function listPatientTimeline(Request $request, $id = null)
  {
    return (new PatientService)->patientTimelineList($request, $id);
  }

  public function addPatientFlag(Request $request, $id = null)
  {
    return (new PatientService)->patientFlagAdd($request, $id);
  }

  public function listPatientFlag(Request $request, $id = null, $flagId = null)
  {
    return (new PatientService)->patientFlagList($request, $id, $flagId);
  }

  public function createPatientCriticalNote(Request $request , $id)
  {
    return (new PatientService)->createPatientCriticalNote($request, $id);
  }








  // Family 
  public function createFamily(Request $request, $id, $familyId = null)
  {
    return (new FamilyService)->familyCreate($request, $id, $familyId);
  }


  // Bitrix APi for getting single deal
  public function getBitrixDealById(Request $request, $patientId)
  {
    if ($patientId) {

      // get deal from the bitrix24 api
      $response = BitrixApi::getDealById($patientId);
      return response()->json($response, 200);
    } else {

      $json = array(
        "error" => "Patient ID is Required."
      );

      return json_encode($json);
    }
  }


  // Bitrix APi for list all deals
  public function getAllBitrixDeals(Request $request, $patientId = null)
  {
    // get deal from the bitrix24 api
    $data = $request->all();
    if ($patientId) {
      // get deal from the bitrix24 api
      $response = BitrixApi::getDealById($patientId);
      return response()->json($response, 200);
    } else if (isset($data["title"])) {
      // get deal by name from the bitrix24 api
      $response = BitrixApi::getDealByName($data["title"]);
      return response()->json($response, 200);
    } else {
      $response = BitrixApi::getAllDeal();
      return response()->json($response, 200);
    }
  }
}
