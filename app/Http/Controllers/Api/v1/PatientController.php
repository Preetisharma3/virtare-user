<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientService;
use App\Http\Requests\Patient\PatientRequest;
use App\Http\Requests\Patient\PatientVitalRequest;
use App\Http\Requests\Patient\PatientProgramRequest;
use App\Http\Requests\Patient\PatientReferalRequest;
use App\Http\Requests\Patient\PatientConditionRequest;
use App\Http\Requests\Patient\PatientInsuranceRequest;
use App\Http\Requests\Patient\PatientInventoryRequest;
use App\Http\Requests\Patient\PatientPhysicianRequest;
use App\Http\Requests\Patient\PatientMedicalHistoryRequest;
use App\Http\Requests\Patient\PatientMedicalRoutineRequest;

class PatientController extends Controller
{

  public function createPatient(PatientRequest $request, $id = null, $familyMemberId = null, $emergencyId = null)
  {
    return (new PatientService)->patientCreate($request, $id, $familyMemberId, $emergencyId);
  }

  public function listPatient(Request $request, $id = null)
  {
    return (new PatientService)->patientList($request, $id);
  }

  public function createPatientCondition(PatientConditionRequest $request, $id, $conditionId = null)
  {
    return (new PatientService)->patientConditionCreate($request, $id, $conditionId);
  }

  public function listPatientCondition(Request $request, $id, $conditionId = null)
  {
    return (new PatientService)->patientConditionList($request, $id, $conditionId);
  }

  public function createPatientReferals(PatientReferalRequest $request, $id, $referalsId = null)
  {
    return (new PatientService)->patientReferalsCreate($request, $id, $referalsId);
  }

  public function listPatientReferals(Request $request, $id, $referalsId = null)
  {
    return (new PatientService)->patientReferalsList($request, $id, $referalsId);
  }

  public function createPatientPhysician(PatientPhysicianRequest $request, $id, $physicianId = null)
  {
    return (new PatientService)->patientPhysicianCreate($request, $id, $physicianId);
  }

  public function listPatientPhysician(Request $request, $id, $physicianId = null)
  {
    return (new PatientService)->patientPhysicianList($request, $id, $physicianId);
  }

  public function createPatientProgram(PatientProgramRequest $request, $id, $programId = null)
  {
    return (new PatientService)->patientProgramCreate($request, $id, $programId);
  }

  public function listPatientProgram(Request $request, $id, $programId = null)
  {
    return (new PatientService)->patientProgramList($request, $id, $programId);
  }

  public function createPatientInventory(PatientInventoryRequest $request, $id, $inventoryId = null)
  {
    return (new PatientService)->patientInventoryCreate($request, $id, $inventoryId);
  }

  public function listPatientInventory(Request $request, $id, $inventoryId = null)
  {
    return (new PatientService)->patientInventoryList($request, $id, $inventoryId);
  }

  public function createPatientVital(Request $request, $id, $vitalId = null)
  {
    return (new PatientService)->patientVitalCreate($request, $id, $vitalId);
  }

  public function listPatientVital(Request $request, $id, $vitalId = null)
  {
    return (new PatientService)->patientVitalList($request, $id, $vitalId);
  }

  public function createPatientMedicalHistory(PatientMedicalHistoryRequest $request, $id, $medicalHistoryId = null)
  {
    return (new PatientService)->patientMedicalHistoryCreate($request, $id, $medicalHistoryId);
  }

  public function listPatientMedicalHistory(Request $request, $id, $medicalHistoryId = null)
  {
    return (new PatientService)->patientMedicalHistoryList($request, $id, $medicalHistoryId);
  }

  public function createPatientMedicalRoutine(PatientMedicalRoutineRequest $request, $id, $medicalRoutineId = null)
  {
    return (new PatientService)->patientMedicalRoutineCreate($request, $id, $medicalRoutineId);
  }

  public function listPatientMedicalRoutine(Request $request, $id, $medicalRoutineId = null)
  {
    return (new PatientService)->patientMedicalRoutineList($request, $id, $medicalRoutineId);
  }

  public function createPatientInsurance(Request $request, $id, $insuranceId = null)
  {
    return (new PatientService)->patientInsuranceCreate($request, $id, $insuranceId);
  }

  public function listPatientInsurance(Request $request, $id, $insuranceId = null)
  {
    return (new PatientService)->patientInsuranceList($request, $id, $insuranceId);
  }
}
