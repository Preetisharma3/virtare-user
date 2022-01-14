<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientService;

class PatientController extends Controller
{

  public function createPatient(Request $request)
  {
    return (new PatientService)->patientCreate($request);
  }

  public function listPatient(Request $request)
  {
    return (new PatientService)->patientList($request);
  }

  public function createPatientCondition(Request $request,$id)
  {
    return (new PatientService)->patientConditionCreate($request,$id);
  }

  public function createPatientReferals(Request $request,$id)
  {
    return (new PatientService)->patientReferalsCreate($request,$id);
  }

  public function createPatientPhysician(Request $request,$id)
  {
    return (new PatientService)->patientPhysicianCreate($request,$id);
  }

  public function createPatientProgram(Request $request,$id)
  {
    return (new PatientService)->patientProgramCreate($request,$id);
  }

  public function createPatientVital(Request $request,$id)
  {
    return (new PatientService)->patientVitalCreate($request,$id);
  }

  public function createPatientInventory(Request $request,$id)
  {
    return (new PatientService)->patientInventoryCreate($request,$id);
  }


}
