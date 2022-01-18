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
use App\Models\Patient\PatientInventory;
use App\Models\Patient\PatientPhysician;
use App\Models\Patient\PatientFamilyMember;
use App\Models\Patient\PatientMedicalHistory;
use App\Models\Patient\PatientMedicalRoutine;
use App\Models\Patient\PatientEmergencyContact;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Patient\PatientVitalTransformer;
use App\Transformers\Patient\PatientMedicalTransformer;
use App\Transformers\Patient\PatientProgramTransformer;
use App\Transformers\Patient\PatientReferalTransformer;
use App\Transformers\Patient\PatientConditionTransformer;
use App\Transformers\Patient\PatientInventoryTransformer;
use App\Transformers\Patient\PatientPhysicianTransformer;
use App\Transformers\Patient\PatientVitalFieldTransformer;
use App\Transformers\Patient\PatientMedicalRoutineTransformer;

class PatientService
{
    public function patientCreate($request)
    {
        DB::beginTransaction();
        try {
            // Added Ptient details in User Table
            $user = [
                'password' => Hash::make('password'), 'email' => $request['email'], 'udid' => Str::random(10),
                'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 4
            ];
            $data = User::create($user);

            // Added  patient details in Patient Table
            $patient = [
                'firstName' => $request->firstName, 'middleName' => $request->middleName, 'lastName' => $request->lastName,
                'dob' => $request->dob, 'genderId' => $request->gender, 'languageId' => $request->language, 'otherLanguageId' => json_encode($request->otherLanguage),
                'nickName' => $request->nickName, 'userId' => $data->id, 'phoneNumber' => $request->phoneNumber, 'contactTypeId' => json_encode($request->contactType),
                'contactTimeId' => $request->contactTime, 'medicalRecordNumber' => $request->medicalRecordNumber, 'countryId' => $request->country,
                'stateId' => $request->state, 'city' => $request->city, 'zipCode' => $request->zipCode, 'appartment' => $request->appartment,
                'address' => $request->address, 'createdBy' => 1, 'height' => $request->height, 'weight' => $request->weight
            ];
            $newData = Patient::create($patient);

            // Added family in user Table
            $familyMemberUser = [

                'password' => Hash::make('password'), 'udid' => Str::random(10), 'email' => $request['familyEmail'],
                'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 4
            ];
            $fam = User::create($familyMemberUser);

            //Added Family in patientFamilyMember Table
            $familyMember = [
                'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'), 'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'), 'genderId' => $request->input('familyGender'),
                'relationId' => $request->input('relation'), 'patientId' => $newData->id, 'createdBy' => 1, 'userId' => $fam->id
            ];
            $familyData = PatientFamilyMember::create($familyMember);

            //Added emergency contact in PatientEmergencyContact table
            $emergencyContact = [
                'fullName' => $request->emergencyFullName, 'phoneNumber' => $request->emergencyPhoneNumber, 'contactTypeId' => json_encode($request->emergencyContactType),
                'contactTimeId' => $request->emergencyContactTime, 'genderId' => $request->emergencyGender, 'patientId' => $newData->id,
                'createdBy' => 1, 'email' => $request->emergencyEmail, 'sameAsFamily' => $request->sameAsFamily
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
                'otherLanguage'
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
    public function patientList($request,$id)
    {
        try {
            if($id){
                $getPatient = Patient::where('id',$id)->with(
                    'user',
                    'family.user',
                    'emergency',
                    'gender',
                    'language',
                    'contactType',
                    'contactTime',
                    'state',
                    'country',
                    'otherLanguage'
                )->first();
                return fractal()->item($getPatient)->transformWith(new PatientTransformer())->toArray();
            }else{
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
                    'otherLanguage'
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
            $conditions = $request->condition;
            foreach ($conditions as $condition) {
                $patient = PatientCondition::create(['conditionId' => $condition, 'patientId' => $id, 'createdBy' => 1]);
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
    public function patientConditionList($request, $id)
    {
        try {
            $getPatient = PatientCondition::where('patientId', $id)->with('patient', 'condition')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Referals
    public function patientReferalsCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'name' => $request->name, 'designationId' => $request->designation, 'email' => $request->email,
                'patientId' => $id, 'fax' => $request->fax, 'createdBy' => 1, 'phoneNumber' => $request->phoneNumber
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
    public function patientReferalsList($request, $id)
    {
        try {
            $getPatient = PatientReferal::where('patientId', $id)->with('patient', 'designation')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add patient Physician
    public function patientPhysicianCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $user = [
                'password' => Hash::make('password'), 'udid' => Str::random(10),
                'email' => $request->email, 'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 5
            ];
            $userData = User::create($user);
            $input = [
                'sameAsReferal' => $request->sameAsAbove, 'patientId' => $id, 'fax' => $request->fax,
                'createdBy' => 1, 'phoneNumber' => $request->phoneNumber, 'userId' => $userData->id, 'designationId' => $request->designation,
                'name' => $request->name
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
    public function patientPhysicianList($request, $id)
    {
        try {
            $getPatient = PatientPhysician::where('patientId', $id)->with('patient', 'designation', 'user')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Program
    public function patientProgramCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'programtId' => $request->program, 'onboardingScheduleDate' => $request->onboardingScheduleDate, 'dischargeDate' => $request->dischargeDate,
                'patientId' => $id, 'createdBy' => 1, 'isActive' => $request->status
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
    public function patientProgramList($request, $id)
    {
        try {
            $getPatient = PatientProgram::where('patientId', $id)->with('patient', 'program')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Inventory
    public function patientInventoryCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'inventoryId' => '1', 'patientId' => $id, 'deviceType' => $request->deviceType,
                'modelNumber' => $request->modelNumber, 'serialNumber' => $request->serialNumber, 'createdBy' => 1,
                'macAddress' => $request->macAddress, 'deviceTime' => $request->deviceTime, 'serverTime' => $request->serverTime
            ];
            $patient = PatientInventory::create($input);
            DB::commit();
            $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceType')->first();
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
    public function patientInventoryList($request, $id)
    {
        try {
            $getPatient = PatientInventory::where('patientId', $id)->with('patient', 'inventory', 'deviceTypes')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
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
            $vitalData = $request->vital;
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
            $getPatient = VitalField::where('patientId', $id)->with('vital')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientVitalTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Vitals
    public function patientVitalList($request, $id)
    {
        try {
            $getPatient = VitalField::where('patientId', $id)->with('vital')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientVitalFieldTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Clinical Data
    public function patientMedicalHistoryCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'history' => $request->history, 'patientId' => $id,  'createdBy' => 1,
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
    public function patientMedicalHistoryList($request, $id)
    {
        try {
            $getPatient = PatientMedicalHistory::where('patientId', $id)->with('patient')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientMedicalRoutineCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'medicine' => $request->medicine, 'frequency' => $request->frequency,  'createdBy' => 1,
                'startDate'=>$request->startDate,'endDate'=>$request->endDate,'patientId'=>$id
            ];
            $patient = PatientMedicalRoutine::create($input);
            DB::commit();
            $getPatient = PatientMedicalHistory::where('id', $patient->id)->with('patient')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientMedicalRoutineList($request, $id)
    {
        try {
            $getPatient = PatientMedicalRoutine::where('patientId', $id)->with('patient')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
