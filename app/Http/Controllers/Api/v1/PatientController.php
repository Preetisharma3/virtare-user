<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientService;
use App\Http\Requests\Patient\PatientRequest;

class PatientController extends Controller
{

  public function createPatient(PatientRequest $request)
  {
    return (new PatientService)->patientCreate($request);
  }

  public function listPatient(Request $request)
  {
    return (new PatientService)->patientList($request);
  }

  public function createPatientCondition(Request $request, $id)
  {
    return (new PatientService)->patientConditionCreate($request, $id);
  }

  public function listPatientCondition(Request $request, $id)
  {
    return (new PatientService)->patientConditionList($request, $id);
  }

  public function createPatientReferals(Request $request, $id)
  {
    return (new PatientService)->patientReferalsCreate($request, $id);
  }

  public function listPatientReferals(Request $request, $id)
  {
    return (new PatientService)->patientReferalsList($request, $id);
  }

  public function createPatientPhysician(Request $request, $id)
  {
    return (new PatientService)->patientPhysicianCreate($request, $id);
  }

  public function listPatientPhysician(Request $request, $id)
  {
    return (new PatientService)->patientPhysicianList($request, $id);
  }

  public function createPatientProgram(Request $request, $id)
  {
    return (new PatientService)->patientProgramCreate($request, $id);
  }

  public function listPatientProgram(Request $request, $id)
  {
    return (new PatientService)->patientProgramList($request, $id);
  }

  public function createPatientInventory(Request $request,$id)
  {
    return (new PatientService)->patientInventoryCreate($request,$id);
  }

  public function listPatientInventory(Request $request,$id)
  {
    return (new PatientService)->patientInventoryList($request,$id);
  }

  public function createPatientVital(Request $request, $id)
  {
    return (new PatientService)->patientVitalCreate($request, $id);
  }

  public function listPatientVital(Request $request, $id)
  {
    return (new PatientService)->patientVitalList($request, $id);
  }

  public function listPatientMedicalHistory(Request $request, $id)
  {
    return (new PatientService)->patientMedicalHistoryList($request, $id);
  }



}
