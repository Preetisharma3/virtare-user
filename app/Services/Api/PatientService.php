<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientCondition;
use App\Models\Patient\PatientFamilyMember;
use App\Models\Patient\PatientEmergencyContact;

class PatientService
{
    public function patientCreate($request)
    {
        try {
            // Added Ptient details in User Table
            $user = ['password' => Hash::make('password'), 'email' => $request->email, 'udid' => Str::random(10), 'emailVerify' => 1, 'createdBy' => 1,"roleId"=>4];
            $data = User::create($user);

            // Added  patient details in Patient Table
            $patient = [
                'firstName' => $request->firstName, 'middleName' => $request->middleName, 'lastName' => $request->lastName,
                'dob' => $request->dob, 'genderId' => $request->gender, 'languageId' => $request->language, 'otherLanguageId' => $request->otherLanguage,
                'nickName' => $request->nickName, 'userId' => $data->id, 'phoneNumber' => $request->phoneNumber, 'contactTypeId' => $request->contactType,
                'contactTimeId' => $request->contactTime, 'medicalRecordNumber' => $request->medicalRecordNumber, 'countryId' => $request->country,
                'stateId' => $request->state, 'city' => $request->city, 'zipCode' => $request->zipCode, 'appartment' => $request->appartment, 'address' => $request->address, 'createdBy' => $data->createdBy,
            ];
            $newData = Patient::create($patient);

            //Added family in user Table
            $familyMemberUser = [
                'password' => Hash::make('password'), 'udid' => Str::random(10), 'email' => $request->familyEmail, 'emailVerify' => 1, 'createdBy' => 1,"roleId"=>4
            ];
            $fam = User::create($familyMemberUser);

            //Added Family in patientFamilyMember Table
            $familyMember = [
                'fullName' => $request->fullName, 'phoneNumber' => $request->familyPhoneNumber, 'contactTypeId' => $request->familyContactType, 'contactTimeId' => $request->familyContactTime, 'genderId' => $request->familyGender,
                'relationId' => $request->relation, 'patientId' => $newData->id, 'isPrimary' => $request->isPrimary, 'createdBy' => $newData->createdBy, 'userId' => $fam->id
            ];
            $familyData = PatientFamilyMember::create($familyMember);

            //Added emergency contact in user table
            $emergencyContactUser = ['password' => Hash::make('password'), 'udid' => Str::random(10), 'email' => $request->emergencyEmail, 'emailVerify' => 1, 'createdBy' => 1,"roleId"=>4];
            $emer = User::create($emergencyContactUser);

            //Added emergency contact in PatientEmergencyContact table
            $emergencyContact = [
                'fullName' => $request->emergencyFullName, 'phoneNumber' => $request->emergencyPhoneNumber, 'contactTypeId' => $request->emergencyContactType, 'contactTimeId' => $request->emergencyContactTime, 'genderId' => $request->emergencyGender, 'patientId' => $newData->id, 'createdBy' => $familyData->createdBy, 'userId' => $emer->id
            ];
            PatientEmergencyContact::create($emergencyContact);

            return response()->json(['message' => 'created successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientConditionCreate($request,$id)
    {
        try{
            $patient = [
                'conditionId' => $request->condition, 'patientId' =>$id
            ];
            $newData = PatientCondition::create($patient);
            return response()->json(['message' => 'created successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
