<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientVital;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientProgram;
use App\Models\Patient\PatientReferal;
use App\Models\Patient\PatientCondition;
use App\Models\Patient\PatientInventory;
use App\Models\Patient\PatientPhysician;
use App\Models\Patient\PatientFamilyMember;
use App\Models\Patient\PatientEmergencyContact;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Patient\PatientProgramTransformer;
use App\Transformers\Patient\PatientReferalTransformer;
use App\Transformers\Patient\PatientConditionTransformer;
use App\Transformers\Patient\PatientInventoryTransformer;
use App\Transformers\Patient\PatientPhysicianTransformer;

class PatientService
{
    public function patientCreate($request)
    {
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
                'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'), 'contactTypeId' =>json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'), 'genderId' => $request->input('familyGender'),
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
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientList($request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientConditionCreate($request, $id)
    {
        try {
            $conditions = $request->condition;
            foreach ($conditions as $condition) {
                $patient = PatientCondition::create(['conditionId' => $condition, 'patientId' => $id, 'createdBy' => 1]);
            }
            $getPatient = PatientCondition::where('id', $patient->id)->with('patient', 'condition')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientReferalsCreate($request, $id)
    {
        try {
            $input = [
                'name' => $request->name, 'designationId' => $request->designation, 'email' => $request->email,
                'patientId' => $id, 'fax' => $request->fax, 'createdBy' => 1, 'phoneNumber' => $request->phoneNumber
            ];
            $patient = PatientReferal::create($input);
            $getPatient = PatientReferal::where('id', $patient->id)->with('patient', 'designation')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientPhysicianCreate($request, $id)
    {
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
            $getPatient = PatientPhysician::where('id', $patient->id)->with('patient', 'designation', 'user')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientProgramCreate($request, $id)
    {
        try {
            $input = [
                'programtId' => $request->program, 'onboardingScheduleDate' => $request->onboardingScheduleDate, 'dischargeDate' => $request->dischargeDate,
                'patientId' => $id, 'createdBy' => 1, 'isActive' => $request->status
            ];
            $patient = PatientProgram::create($input);
            $getPatient = PatientProgram::where('id', $patient->id)->with('patient', 'program')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientInventoryCreate($request, $id)
    {
        try {
            $input = [
                'inventoryId' => $request->inventory, 'patientId' => $id, 'deviceType' => $request->deviceType,
                'modelNumber' => $request->modelNumber, 'serialNumber' => $request->serialNumber, 'createdBy' => 1,
                'macAddress' => $request->macAddress, 'deviceTime' => $request->deviceTime, 'serverTime' => $request->serverTime
            ];
            $patient = PatientInventory::create($input);
            $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceType')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientVitalCreate($request, $id)
    {
        try {
            $input = [
                'staffId' => $request->staff, 'sameAsReferal' => $request->sameAsAbove, 'email' => $request->email,
                'patientId' => $id, 'fax' => $request->fax, 'createdBy' => 1, 'phoneNumber' => $request->phoneNumber
            ];
            $patient = PatientVital::create($input);
            $getPatient = PatientVital::where('id', $patient->id)->with('patient', 'globalCode')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
