<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Vital\VitalField;
use Illuminate\Support\Facades\DB;
use App\Models\Patient\PatientVital;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientProgram;
use App\Models\Patient\PatientReferal;
use App\Models\Patient\PatientCondition;
use App\Models\Patient\PatientInsurance;
use App\Models\Patient\PatientInventory;
use App\Models\Patient\PatientPhysician;
use App\Models\Patient\PatientFamilyMember;
use App\Models\Patient\PatientMedicalHistory;
use App\Models\Patient\PatientMedicalRoutine;
use App\Models\Patient\PatientEmergencyContact;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Patient\PatientMedicalTransformer;
use App\Transformers\Patient\PatientProgramTransformer;
use App\Transformers\Patient\PatientReferalTransformer;
use App\Transformers\Patient\PatientConditionTransformer;
use App\Transformers\Patient\PatientInsuranceTransformer;
use App\Transformers\Patient\PatientInventoryTransformer;
use App\Transformers\Patient\PatientPhysicianTransformer;
use App\Transformers\Patient\PatientVitalFieldTransformer;
use App\Transformers\Patient\PatientMedicalRoutineTransformer;

class PatientService
{
    // Add Patient
    public function patientCreate($request)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            // Added Ptient details in User Table
            $user = [
                'password' => Hash::make('password'), 'email' => $request->input('email'),'udid' => $udid,
                'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 4
            ];
            $data = User::create($user);

            // Added  patient details in Patient Table
            $patient = [
                'firstName' => $request->input('firstName'), 'middleName' => $request->input('middleName'), 'lastName' => $request->input('lastName'),
                'dob' => $request->input('dob'), 'genderId' => $request->input('gender'), 'languageId' => $request->input('language'), 'otherLanguageId' => json_encode($request->input('otherLanguage')),
                'nickName' => $request->input('nickName'), 'userId' => $data->id, 'phoneNumber' => $request->input('phoneNumber'), 'contactTypeId' => json_encode($request->input('contactType')),
                'contactTimeId' => $request->input('contactTime'), 'medicalRecordNumber' => $request->input('medicalRecordNumber'), 'countryId' => $request->input('country'),
                'stateId' => $request->input('state'), 'city' => $request->input('city'), 'zipCode' => $request->input('zipCode'), 'appartment' => $request->input('appartment'),
                'address' => $request->input('address'), 'createdBy' => 1, 'height' => $request->input('height'), 'weight' => $request->input('weight'),'udid' => $udid
            ];
            $newData = Patient::create($patient);

            // Added family in user Table
            $familyMemberUser = [

                'password' => Hash::make('password'), 'udid' => $udid, 'email' => $request->input('familyEmail'),
                'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 4
            ];
            $fam = User::create($familyMemberUser);

            //Added Family in patientFamilyMember Table
            $familyMember = [
                'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $newData->id,
                 'createdBy' => 1, 'userId' => $fam->id,'udid' => $udid
            ];
            $familyData = PatientFamilyMember::create($familyMember);

            //Added emergency contact in PatientEmergencyContact table
            $emergencyContact = [
                'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'), 'patientId' => $newData->id,
                'createdBy' => 1, 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily'),'udid' => $udid
            ];
            PatientEmergencyContact::create($emergencyContact);
            DB::commit();

            $getPatient = Patient::where('id', $newData->id)->with(
                'user',
                'family.user',
                'emergency',
                'gender',
                'language',
                'contactType',
                'contactTime',
                'state',
                'country',
                'otherLanguage',
                'vitals.vital',
                'flags.flag'
            )->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    // Patient Listing
    public function patientList($request, $id)
    {
        try {
            if ($id) {
                $getPatient = Patient::where('id', $id)->with(
                    'user',
                    'family.user',
                    'emergency',
                    'gender',
                    'language',
                    'contactType',
                    'contactTime',
                    'state',
                    'country',
                    'otherLanguage',
                    'vitals.vital',
                    'flags.flag'
                )->first();
                return fractal()->item($getPatient)->transformWith(new PatientTransformer())->toArray();
            } else {
                $getPatient = Patient::with(
                    'user',
                    'family.user',
                    'emergency',
                    'gender',
                    'language',
                    'contactType',
                    'contactTime',
                    'state',
                    'country',
                    'otherLanguage',
                    'vitals.vital',
                    'flags.flag'
                )->get();
                return fractal()->collection($getPatient)->transformWith(new PatientTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Condition
    public function patientConditionCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $conditions = $request->input('condition');
            foreach ($conditions as $condition) {
                $patient = PatientCondition::create(['conditionId' => $condition, 'patientId' => $id, 'createdBy' => 1,'udid' => $udid]);
            }
            DB::commit();
            $getPatient = PatientCondition::where('patientId', $id)->with('patient', 'condition')->get();
            $userdata = fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient condition
    public function patientConditionList($request, $id, $conditionId)
    {
        try {
            if ($conditionId) {
                $getPatient = PatientCondition::where('id', $conditionId)->with('patient', 'condition')->first();
                return fractal()->item($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            } else {
                $getPatient = PatientCondition::where('patientId', $id)->with('patient', 'condition')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Referals
    public function patientReferalsCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input = [
                'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                'patientId' => $id, 'fax' => $request->input('fax'), 'createdBy' => 1, 'phoneNumber' => $request->input('phoneNumber'),'udid' => $udid
            ];
            
            $patient = PatientReferal::create($input);
            DB::commit();
            $getPatient = PatientReferal::where('id', $patient->id)->with('patient', 'designation')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient referals
    public function patientReferalsList($request, $id, $referalsId)
    {
        try {
            if ($referalsId) {
                $getPatient = PatientReferal::where('id', $referalsId)->with('patient', 'designation')->first();
                return fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
            } else {
                $getPatient = PatientReferal::where('patientId', $id)->with('patient', 'designation')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add patient Physician
    public function patientPhysicianCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $user = [
                'password' => Hash::make('password'), 'udid' => Str::random(10),
                'email' => $request->input('email'), 'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 5,'udid' => $udid
            ];
            $userData = User::create($user);
            $input = [
                'sameAsReferal' => $request->input('sameAsAbove'), 'patientId' => $id, 'fax' => $request->input('fax'),
                'createdBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'userId' => $userData->id, 'designationId' => $request->input('designation'),
                'name' => $request->input('name'),'udid' => $udid
            ];
            $patient = PatientPhysician::create($input);
            DB::commit();
            $getPatient = PatientPhysician::where('id', $patient->id)->with('patient', 'designation', 'user')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Physician
    public function patientPhysicianList($request, $id, $physicianId)
    {
        try {
            if ($physicianId) {
                $getPatient = PatientPhysician::where('id', $physicianId)->with('patient', 'designation', 'user')->first();
                return fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
            } else {
                $getPatient = PatientPhysician::where('patientId', $id)->with('patient', 'designation', 'user')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Program
    public function patientProgramCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input = [
                'programtId' => $request->input('program'), 'onboardingScheduleDate' => $request->input('onboardingScheduleDate'), 'dischargeDate' => $request->input('dischargeDate'),
                'patientId' => $id, 'createdBy' => 1, 'isActive' => $request->input('status'),'udid' => $udid
            ];
            $patient = PatientProgram::create($input);
            DB::commit();
            $getPatient = PatientProgram::where('id', $patient->id)->with('patient', 'program')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Program
    public function patientProgramList($request, $id, $programId)
    {
        try {
            if ($programId) {
                $getPatient = PatientProgram::where('id', $programId)->with('patient', 'program')->first();
                return fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
            } else {
                $getPatient = PatientProgram::where('patientId', $id)->with('patient', 'program')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Inventory
    public function patientInventoryCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input = [
                'inventoryId' => $request->input('inventory'), 'patientId' => $id, 'deviceType' => $request->input('deviceType'),
                'modelNumber' => $request->input('modelNumber'), 'serialNumber' => $request->input('serialNumber'), 'createdBy' => 1,
                'macAddress' => $request->input('macAddress'), 'deviceTime' => $request->input('deviceTime'), 'serverTime' => $request->input('serverTime'),'udid' => $udid
            ];
            $patient = PatientInventory::create($input);
            DB::commit();
            $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceTypes')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Inventory
    public function patientInventoryList($request, $id, $inventoryId)
    {
        try {
            if ($inventoryId) {
                $getPatient = PatientInventory::where('id', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                return fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            } else {
                $getPatient = PatientInventory::where('patientId', $id)->with('patient', 'inventory', 'deviceTypes')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Vitals
    public function patientVitalCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $vitalData = $request->input('vital');
            foreach ($vitalData as $vital) {
                $vitals = [
                    'patientId' => $id, 'createdBy' => 1, 'udid' => $udid, 'name' => $vital['name']
                ];
                $vitalField = VitalField::create($vitals);
                $inputs = [
                    'vitalTypeId' => $vital['vitalType'], 'typeId' => $vitalField->id, 'createdBy' => 1, 'udid' => $udid, 'value' => $vital['value']
                ];
                $patient = PatientVital::create($inputs);
            }
            DB::commit();
            $getPatient = VitalField::where('patientId', $id)->with('vital')->get();
            $userdata = fractal()->collection($getPatient)->transformWith(new PatientVitalFieldTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Vitals
    public function patientVitalList($request, $id, $vitalId)
    {
        try {
            if ($vitalId) {
                $getPatient = VitalField::where('patientId', $id)->with('vital')->first();
                return fractal()->item($getPatient)->transformWith(new PatientVitalFieldTransformer())->toArray();
            } else {
                $getPatient = VitalField::where('patientId', $id)->with('vital')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientVitalFieldTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Clinical Data
    public function patientMedicalHistoryCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input = [
                'history' => $request->input('history'), 'patientId' => $id,  'createdBy' => 1,'udid' => $udid
            ];
            $patient = PatientMedicalHistory::create($input);
            DB::commit();
            $getPatient = PatientMedicalHistory::where('id', $patient->id)->with('patient')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Medical History
    public function patientMedicalHistoryList($request, $id, $medicalHistoryId)
    {
        try {
            if ($medicalHistoryId) {
                $getPatient = PatientMedicalHistory::where('id', $medicalHistoryId)->with('patient')->first();
                return fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
            } else {
                $getPatient = PatientMedicalHistory::where('patientId', $id)->with('patient')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientMedicalRoutineCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input = [
                'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'createdBy' => 1,
                'startDate' => $request->input('startDate'), 'endDate' => $request->input('endDate'), 'patientId' => $id,'udid' => $udid
            ];
            $patient = PatientMedicalRoutine::create($input);
            DB::commit();
            $getPatient = PatientMedicalRoutine::where('id', $patient->id)->with('patient')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Medical Routine 
    public function patientMedicalRoutineList($request, $id, $medicalRoutineId)
    {
        try {
            if ($medicalRoutineId) {
                $getPatient = PatientMedicalRoutine::where('id', $medicalRoutineId)->with('patient')->first();
                return fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
            } else {
                $getPatient = PatientMedicalRoutine::where('patientId', $id)->with('patient')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Insurance
    public function patientInsuranceCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $insurance = $request->input('insurance');
            foreach ($insurance as $value) {
                $input = [
                    'insuranceNumber' => $value['insuranceNumber'], 'expirationDate' => $value['expirationDate'],  'createdBy' => 1,
                    'insuranceNameId' => $value['insuranceName'], 'insuranceTypeId' => $value['insuranceType'], 'patientId' => $id, 'udid' => $udid
                ];
                $patient = PatientInsurance::create($input);
            }
            DB::commit();
            $getPatient = PatientInsurance::where('id', $patient->id)->with('patient')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Insurance
    public function patientInsuranceList($request, $id, $medicalRoutineId)
    {
        try {
            if ($medicalRoutineId) {
                $getPatient = PatientInsurance::where('id', $medicalRoutineId)->with('patient', 'insuranceName', 'insuranceType')->first();
                return fractal()->item($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
            } else {
                $getPatient = PatientInsurance::where('patientId', $id)->with('patient', 'insuranceName', 'insuranceType')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    
}
