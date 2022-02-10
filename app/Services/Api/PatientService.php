<?php

namespace App\Services\Api;

use App\Helper;
use Exception;
use App\Models\Tag\Tag;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use App\Models\Patient\PatientFlag;
use App\Models\Patient\PatientVital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientDevice;
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
use App\Transformers\Patient\PatientVitalTransformer;
use App\Transformers\Patient\PatientDeviceTransformer;
use App\Transformers\Patient\PatientMedicalTransformer;
use App\Transformers\Patient\PatientProgramTransformer;
use App\Transformers\Patient\PatientReferalTransformer;
use App\Transformers\Patient\PatientConditionTransformer;
use App\Transformers\Patient\PatientInsuranceTransformer;
use App\Transformers\Patient\PatientInventoryTransformer;
use App\Transformers\Patient\PatientPhysicianTransformer;
use App\Transformers\Patient\PatientMedicalRoutineTransformer;

class PatientService
{
    // Add And Update  Patient 
    public function patientCreate($request, $id, $familyMemberId, $emergencyId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                // Added Ptient details in User Table
                $user = [
                    'password' => Hash::make('password'), 'email' => $request->input('email'), 'udid' => Str::uuid()->toString(),
                    'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 4
                ];
                $data = User::create($user);

                // Added  patient details in Patient Table
                $patient = [
                    'firstName' => $request->input('firstName'), 'middleName' => $request->input('middleName'), 'lastName' => $request->input('lastName'),
                    'dob' => $request->input('dob'), 'genderId' => $request->input('gender'), 'languageId' => $request->input('language'), 'otherLanguageId' => json_encode($request->input('otherLanguage')),
                    'nickName' => $request->input('nickName'), 'userId' => $data->id, 'phoneNumber' => $request->input('phoneNumber'), 'contactTypeId' => json_encode($request->input('contactType')),
                    'contactTimeId' => $request->input('contactTime'), 'medicalRecordNumber' => $request->input('medicalRecordNumber'), 'countryId' => $request->input('country'),
                    'stateId' => $request->input('state'), 'city' => $request->input('city'), 'zipCode' => $request->input('zipCode'), 'appartment' => $request->input('appartment'),
                    'address' => $request->input('address'), 'createdBy' => Auth::id(), 'height' => $request->input('height'), 'weight' => $request->input('weight'), 'udid' => Str::uuid()->toString()
                ];
                $newData = Patient::create($patient);

                //Added Family in patientFamilyMember Table
                $userData = User::where([['email', $request->input('familyEmail')], ['roleId', 4]])->first();
                if ($userData) {
                    $userEmail = $userData->id;
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                        'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                        'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $newData->id,
                        'createdBy' => Auth::id(), 'userId' => $userEmail, 'udid' => Str::uuid()->toString(), 'isPrimary' => 1
                    ];
                    $familyData = PatientFamilyMember::create($familyMember);
                } else {
                    // Added family in user Table
                    $familyMemberUser = [
                        'password' => Hash::make('password'), 'udid' => Str::uuid()->toString(), 'email' => $request->input('familyEmail'),
                        'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 4
                    ];
                    $fam = User::create($familyMemberUser);
                    //Added Family in patientFamilyMember Table
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                        'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                        'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $newData->id,
                        'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => Str::uuid()->toString()
                    ];
                    $familyData = PatientFamilyMember::create($familyMember);
                }

                //Added emergency contact in PatientEmergencyContact table
                $emergencyContact = [
                    'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                    'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'), 'patientId' => $newData->id,
                    'createdBy' => Auth::id(), 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily'), 'udid' => Str::uuid()->toString()
                ];
                PatientEmergencyContact::create($emergencyContact);
                $getPatient = Patient::where('udid', $newData->udid)->with(
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
                    'flags.flag'
                )->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientTransformer())->toArray();
                $message = ['message' => 'created successfully'];
            } else {
                $usersId = Patient::where('udid', $id)->first();
                $uId = $usersId->userId;
                // Updated Ptient details in User Table
                $user = [
                    'email' => $request->input('email'),
                    'updatedBy' => 1
                ];
                $userUpdate = User::where('id', $uId)->update($user);

                // Updated  patient details in Patient Table
                $patient = [
                    'firstName' => $request->input('firstName'), 'middleName' => $request->input('middleName'), 'lastName' => $request->input('lastName'),
                    'dob' => $request->input('dob'), 'genderId' => $request->input('gender'), 'languageId' => $request->input('language'), 'otherLanguageId' => json_encode($request->input('otherLanguage')),
                    'nickName' => $request->input('nickName'), 'phoneNumber' => $request->input('phoneNumber'), 'contactTypeId' => json_encode($request->input('contactType')),
                    'contactTimeId' => $request->input('contactTime'), 'medicalRecordNumber' => $request->input('medicalRecordNumber'), 'countryId' => $request->input('country'),
                    'stateId' => $request->input('state'), 'city' => $request->input('city'), 'zipCode' => $request->input('zipCode'), 'appartment' => $request->input('appartment'),
                    'address' => $request->input('address'), 'updatedBy' => 1, 'height' => $request->input('height'), 'weight' => $request->input('weight')
                ];
                $newData = Patient::where('udid', $id)->update($patient);

                // Updated family in user Table
                $usersId = PatientFamilyMember::where('udid', $familyMemberId)->first();
                $familyId = $usersId->userId;
                $familyMemberUser = [
                    'email' => $request->input('familyEmail'),
                    'updatedBy' => 1
                ];
                $fam = User::where('id', $familyId)->update($familyMemberUser);

                //Updated Family in patientFamilyMember Table
                $familyMember = [
                    'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                    'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                    'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'),
                    'updatedBy' => 1,
                ];
                $familyData = PatientFamilyMember::where('udid', $familyMemberId)->update($familyMember);

                //Updated emergency contact in PatientEmergencyContact table
                $emergencyContact = [
                    'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                    'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'),
                    'updatedBy' => 1, 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily')
                ];
                $emergency = PatientEmergencyContact::where('udid', $emergencyId)->update($emergencyContact);
                $getPatient = Patient::where('udid', $id)->with(
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
                    'flags.flag'
                )->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientTransformer())->toArray();
                $message = ['message' => 'update successfully'];
            }
            DB::commit();
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
                $getPatient = Patient::where('udid', $id)->with(
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
                    'flags.flag',
                    'inventories.inventory'
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
                    'flags.flag',
                    'inventories.inventory'
                )->get();
                return fractal()->collection($getPatient)->transformWith(new PatientTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete patient
    public function patientDelete($request, $id)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            $patient = Patient::where('udid', $id)->first();
            $user = $patient->userId;
            $document = Document::where([['referanceId', $id], ['entityType', 'patient']])->first();
            $tag = $document->id;
            $tables = [
                User::where('id', $user),
                PatientVital::where('id', $id),
                PatientProgram::where('patientId', $id),
                PatientInsurance::where('patientId', $id),
                PatientInventory::where('patientId', $id),
                PatientPhysician::where('patientId', $id),
                PatientFamilyMember::where('patientId', $id),
                PatientMedicalHistory::where('patientId', $id),
                PatientMedicalRoutine::where('patientId', $id),
                PatientFlag::where('patientId', $id),
                PatientCondition::where('patientId', $id),
                PatientEmergencyContact::where('patientId', $id),
                PatientEmergencyContact::where('patientId', $id),
                PatientEmergencyContact::where('patientId', $id),
                PatientEmergencyContact::where('patientId', $id),
                Tag::where('documentId', $tag),
            ];
            foreach ($tables as $table) {
                $table->update($data);
                $table->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Condition
    public function patientConditionCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                PatientCondition::where('patientId', $patientId)->delete();
                $udid = Str::uuid()->toString();
                $conditions = $request->input('condition');
                foreach ($conditions as $condition) {
                    $input = [
                        'conditionId' => $condition,
                        'patientId' => $patientId, 'udid' => $udid, 'createdBy' => Auth::id()
                    ];
                    PatientCondition::create($input);
                    $getPatient = PatientCondition::where('patientId', $patientId)->with('patient')->get();
                    $userdata = fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
                    $message = ['message' => 'create successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                PatientCondition::where('patientId', $patientId)->delete();
                $udid = Str::uuid()->toString();
                $conditions = $request->input('condition');
                foreach ($conditions as $condition) {
                    $input = [
                        'conditionId' => $condition,
                        'patientId' => $patientId, 'udid' => $udid, 'createdBy' => 1
                    ];
                    PatientCondition::create($input);
                    $getPatient = PatientCondition::where('patientId', $patientId)->with('patient')->get();
                    $userdata = fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
                    $message = ['message' => 'create successfully'];
                }
            }

            DB::commit();
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

            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                $getPatient = PatientCondition::where('patientId', $patientId)->with('patient', 'condition')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                $getPatient = PatientCondition::where('patientId', $patientId)->with('patient', 'condition')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Referals
    public function patientReferalsCreate($request, $id, $referalsId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$referalsId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                        'patientId' => $patientId, 'fax' => $request->input('fax'), 'createdBy' => Auth::id(), 'phoneNumber' => $request->input('phoneNumber'), 'udid' => $udid
                    ];
                    $patient = PatientReferal::create($input);
                    $getPatient = PatientReferal::where('id', $patient->id)->with('patient', 'designation')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $referal = PatientReferal::where('udid', $referalsId)->first();
                    $referalId = $referal->id;
                    $input = [
                        'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                        'fax' => $request->input('fax'), 'updatedBy' => 1, 'phoneNumber' => $request->input('phoneNumber')
                    ];
                    $patient = PatientReferal::where('id', $referalId)->update($input);
                    $getPatient = PatientReferal::where('id', $referalId)->with('patient', 'designation')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                    $message = ['message' => 'update successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if (!$referalsId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                        'patientId' => $patientId, 'fax' => $request->input('fax'), 'createdBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'udid' => $udid
                    ];
                    $patient = PatientReferal::create($input);
                    $getPatient = PatientReferal::where('id', $patient->id)->with('patient', 'designation')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $referal = PatientReferal::where('udid', $referalsId)->first();
                    $referalId = $referal->id;
                    $input = [
                        'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                        'fax' => $request->input('fax'), 'updatedBy' => 1, 'phoneNumber' => $request->input('phoneNumber')
                    ];
                    $patient = PatientReferal::where('id', $referalId)->update($input);
                    $getPatient = PatientReferal::where('id', $referalId)->with('patient', 'designation')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                    $message = ['message' => 'update successfully'];
                }
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Referals
    public function patientReferalsList($request, $id, $referalsId)
    {
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if ($referalsId) {
                    $referal = PatientReferal::where('udid', $referalsId)->first();
                    $referalId = $referal->id;
                    $getPatient = PatientReferal::where('id', $referalId)->with('patient', 'designation')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                } else {
                    $getPatient = PatientReferal::where('patientId', $patientId)->with('patient', 'designation')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if ($referalsId) {
                    $referal = PatientReferal::where('udid', $referalsId)->first();
                    $referalId = $referal->id;
                    $getPatient = PatientReferal::where('id', $referalId)->with('patient', 'designation')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                } else {
                    $getPatient = PatientReferal::where('patientId', $patientId)->with('patient', 'designation')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Referals
    public function patientReferalsDelete($request, $id, $referalsId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientReferal::where('udid', $referalsId)->update($data);
                PatientReferal::where('udid', $referalsId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientReferal::find($referalsId)->update($data);
                PatientReferal::find($referalsId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update patient Physician
    public function patientPhysicianCreate($request, $id, $physicianId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$physicianId) {
                    $udid = Str::uuid()->toString();
                    $user = [
                        'password' => Hash::make('password'),
                        'email' => $request->input('email'), 'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 5, 'udid' => $udid
                    ];
                    $userData = User::create($user);
                    $input = [
                        'sameAsReferal' => $request->input('sameAsAbove'), 'patientId' => $patientId, 'fax' => $request->input('fax'),
                        'createdBy' => Auth::id(), 'phoneNumber' => $request->input('phoneNumber'), 'userId' => $userData->id, 'designationId' => $request->input('designation'),
                        'name' => $request->input('name'), 'udid' => $udid
                    ];
                    $patient = PatientPhysician::create($input);
                    $getPatient = PatientPhysician::where('id', $patient->id)->with('patient', 'designation', 'user')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $usersId = PatientPhysician::where('udid', $physicianId)->first();
                    $uId = $usersId->userId;
                    $user = [
                        'email' => $request->input('email'), 'updatedBy' => Auth::id(),
                    ];
                    $userData = User::where('id', $uId)->update($user);
                    $input = [
                        'sameAsReferal' => $request->input('sameAsAbove'), 'fax' => $request->input('fax'),
                        'updatedBy' => Auth::id(), 'phoneNumber' => $request->input('phoneNumber'), 'designationId' => $request->input('designation'),
                        'name' => $request->input('name'),
                    ];
                    $patient = PatientPhysician::where('udid', $physicianId)->update($input);
                    $getPatient = PatientPhysician::where('udid', $physicianId)->with('patient', 'designation', 'user')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                    $message = ['message' => 'update successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if (!$physicianId) {
                    $udid = Str::uuid()->toString();
                    $user = [
                        'password' => Hash::make('password'),
                        'email' => $request->input('email'), 'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 5, 'udid' => $udid
                    ];
                    $userData = User::create($user);
                    $input = [
                        'sameAsReferal' => $request->input('sameAsAbove'), 'patientId' => $patientId, 'fax' => $request->input('fax'),
                        'createdBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'userId' => $userData->id, 'designationId' => $request->input('designation'),
                        'name' => $request->input('name'), 'udid' => $udid
                    ];
                    $patient = PatientPhysician::create($input);
                    $getPatient = PatientPhysician::where('id', $patient->id)->with('patient', 'designation', 'user')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $usersId = PatientPhysician::where('udid', $physicianId)->first();
                    $uId = $usersId->userId;
                    $user = [
                        'email' => $request->input('email'), 'updatedBy' => 1,
                    ];
                    $userData = User::where('id', $uId)->update($user);
                    $input = [
                        'sameAsReferal' => $request->input('sameAsAbove'), 'fax' => $request->input('fax'),
                        'updatedBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'designationId' => $request->input('designation'),
                        'name' => $request->input('name'),
                    ];
                    $patient = PatientPhysician::where('udid', $physicianId)->update($input);
                    $getPatient = PatientPhysician::where('udid', $physicianId)->with('patient', 'designation', 'user')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                    $message = ['message' => 'update successfully'];
                }
            }

            DB::commit();
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
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if ($physicianId) {
                    $getPatient = PatientPhysician::where('udid', $physicianId)->with('patient', 'designation', 'user')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                } else {
                    $getPatient = PatientPhysician::where('patientId', $patientId)->with('patient', 'designation', 'user')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if ($physicianId) {
                    $getPatient = PatientPhysician::where('udid', $physicianId)->with('patient', 'designation', 'user')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                } else {
                    $getPatient = PatientPhysician::where('patientId', $patientId)->with('patient', 'designation', 'user')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Physician
    public function patientPhysicianDelete($request, $id, $physicianId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientPhysician::where('udid', $physicianId)->update($data);
                PatientPhysician::where('udid', $physicianId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientPhysician::where('udid', $physicianId)->update($data);
                PatientPhysician::where('udid', $physicianId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Program
    public function patientProgramCreate($request, $id, $programId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$programId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'programtId' => $request->input('program'), 'onboardingScheduleDate' => $request->input('onboardingScheduleDate'), 'dischargeDate' => $request->input('dischargeDate'),
                        'patientId' => $patientId, 'createdBy' => Auth::id(), 'isActive' => $request->input('status'), 'udid' => $udid
                    ];
                    $patient = PatientProgram::create($input);
                    $result = DB::select(
                        "CALL getPatientProgram('" . $patient->id . "','" . $patientId . "')"
                    );
                    $userdata = fractal()->collection($result)->transformWith(new PatientProgramTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'programtId' => $request->input('program'), 'onboardingScheduleDate' => $request->input('onboardingScheduleDate'), 'dischargeDate' => $request->input('dischargeDate'),
                        'updatedBy' => Auth::id(), 'isActive' => $request->input('status')
                    ];
                    PatientProgram::where('udid', $programId)->update($input);
                    $patient = PatientProgram::where('udid', $programId)->first();
                    $result = DB::select(
                        "CALL getPatientProgram('" . $patient->id . "','" . $patient->patientId . "')"
                    );
                    $userdata = fractal()->collection($result)->transformWith(new PatientProgramTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            } else {
                if (!$programId) {
                    $patient = Patient::where('udid', $id)->first();
                    $patientId = $patient->id;
                    $udid = Str::uuid()->toString();
                    $input = [
                        'programtId' => $request->input('program'), 'onboardingScheduleDate' => $request->input('onboardingScheduleDate'), 'dischargeDate' => $request->input('dischargeDate'),
                        'patientId' => $patientId, 'createdBy' => 1, 'isActive' => $request->input('status'), 'udid' => $udid
                    ];
                    $patient = PatientProgram::create($input);
                    $result = DB::select(
                        "CALL getPatientProgram('" . $patient->id . "','" . $patientId . "')"
                    );
                    $userdata = fractal()->collection($result)->transformWith(new PatientProgramTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'programtId' => $request->input('program'), 'onboardingScheduleDate' => $request->input('onboardingScheduleDate'), 'dischargeDate' => $request->input('dischargeDate'),
                        'updatedBy' => 1, 'isActive' => $request->input('status')
                    ];
                    PatientProgram::where('udid', $programId)->update($input);
                    $patient = PatientProgram::where('udid', $programId)->first();
                    $result = DB::select(
                        "CALL getPatientProgram('" . $patient->id . "','" . $patient->patientId . "')"
                    );
                    $userdata = fractal()->collection($result)->transformWith(new PatientProgramTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            }

            DB::commit();
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
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                $program = PatientProgram::where('udid', $programId)->first();
                $result = DB::select(
                    "CALL getPatientProgram('" . $program->id . "','" . $patientId . "')"
                );
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if (!$programId) {
                    $result = DB::select(
                        "CALL getPatientProgram('" . '' . "','" . $patientId . "')"
                    );
                } else {
                    $program = PatientProgram::where('udid', $programId)->first();
                    $result = DB::select(
                        "CALL getPatientProgram('" . $program->id . "','" . $patientId . "')"
                    );
                }
            }
            return fractal()->collection($result)->transformWith(new PatientProgramTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Program
    public function patientProgramDelete($request, $id, $programId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1];
                PatientProgram::where('udid', $programId)->update($data);
                PatientProgram::where('udid', $programId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1];
                PatientProgram::where('udid', $programId)->update($data);
                PatientProgram::where('udid', $programId)->delete();
            }

            DB::commit();
            return response()->json(['message' => 'deleted successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Inventory
    public function patientInventoryCreate($request, $id, $inventoryId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$inventoryId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'inventoryId' => $request->input('inventory'), 'patientId' => $patientId, 'createdBy' => Auth::id(), 'udid' => $udid
                    ];
                    $patient = PatientInventory::create($input);
                    $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceTypes')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'isActive' => $request->input('status'),  'updatedBy' => Auth::id(),
                    ];
                    $patient = PatientInventory::where('udid', $inventoryId)->update($input);
                    $getPatient = PatientInventory::where('udid', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if (!$inventoryId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'inventoryId' => $request->input('inventory'), 'patientId' => $patientId, 'createdBy' => 1, 'udid' => $udid
                    ];
                    $patient = PatientInventory::create($input);
                    $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceTypes')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'isActive' => $request->input('status'),  'updatedBy' => 1,
                    ];
                    $patient = PatientInventory::where('udid', $inventoryId)->update($input);
                    $getPatient = PatientInventory::where('udid', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            }
            DB::commit();
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
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if ($inventoryId) {
                    $getPatient = PatientInventory::where('udid', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                } else {
                    $getPatient = PatientInventory::where('patientId', $patientId)->with('patient', 'inventory', 'deviceTypes')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                }
            } else {
                if ($inventoryId) {
                    $getPatient = PatientInventory::where('udid', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                } else {
                    $patient = Patient::where('udid', $id)->first();
                    $patientId = $patient->id;
                    $getPatient = PatientInventory::where('patientId', $patientId)->with('patient', 'inventory', 'deviceTypes')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Inventory
    public function patientInventoryDelete($request, $id, $inventoryId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientInventory::where('udid', $inventoryId)->update($data);
                PatientInventory::where('udid', $inventoryId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientInventory::where('udid', $inventoryId)->update($data);
                PatientInventory::where('udid', $inventoryId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Vitals
    public function patientVitalCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            if ($id) {
                $udid = Str::uuid()->toString();
                $dataVital = $request->vital;
                foreach ($dataVital as $vital) {
                    $takeTime = Helper::date($vital['takeTime']);
                    $startTime = Helper::date($vital['startTime']);
                    $endTime = Helper::date($vital['endTime']);
                    $data = [
                        'vitalFieldId' => $vital['type'],
                        'createdBy' => 1,
                        'udid' => $udid,
                        'value' => $vital['value'],
                        'patientId' => $id,
                        'units' => $vital['units'],
                        'takeTime' => $takeTime,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                        'addType' => $vital['addType'],
                        'createdType' => $vital['createdType'],
                        'comment' => $vital['comment'],
                        'deviceInfo' => json_encode($vital['deviceInfo'])
                    ];
                    $vital = PatientVital::create($data);
                    $result = DB::select(
                        "CALL getPatientVital('" . $id . "','" . $request->type . "')"
                    );
                }
            } else {
                $userId = Auth::id();
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                $udid = Str::uuid()->toString();
                $dataVital = $request->vital;
                foreach ($dataVital as $vital) {
                    $takeTime = Helper::date($vital['takeTime']);
                    $startTime = Helper::date($vital['startTime']);
                    $endTime = Helper::date($vital['endTime']);
                    $data = [
                        'vitalFieldId' => $vital['type'],
                        'createdBy' => $userId,
                        'udid' => $udid,
                        'value' => $vital['value'],
                        'patientId' => $patientId,
                        'units' => $vital['units'],
                        'takeTime' => $takeTime,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                        'addType' => $vital['addType'],
                        'createdType' => $vital['createdType'],
                        'comment' => $vital['comment'],
                        'deviceInfo' => json_encode($vital['deviceInfo'])
                    ];
                    $vital = PatientVital::create($data);
                    $result = DB::select(
                        "CALL getPatientVital('" . $patientId . "','" . $request->type . "')"
                    );
                }
            }
            $userdata = fractal()->collection($result)->transformWith(new PatientVitalTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            DB::commit();
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
            if ($id) {
                $result = DB::select(
                    "CALL getPatientVital('" . $id . "','" . $request->type . "')"
                );
            } else {
                $userId = Auth::id();
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                $result = DB::select(
                    "CALL getPatientVital('" . $patientId . "','" . $request->type . "')"
                );
            }
            return fractal()->collection($result)->transformWith(new PatientVitalTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Vitals
    public function patientVitalDelete($request, $id, $vitalId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientVital::find($vitalId)->update($data);
                PatientVital::find($vitalId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientVital::find($vitalId)->update($data);
                PatientVital::find($vitalId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Clinical Data
    public function patientMedicalHistoryCreate($request, $id, $medicalHistoryId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$medicalHistoryId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'history' => $request->input('history'), 'patientId' => $patientId,  'createdBy' => Auth::id(), 'udid' => $udid
                    ];
                    $patient = PatientMedicalHistory::create($input);
                    $getPatient = PatientMedicalHistory::where('id', $patient->id)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'history' => $request->input('history'), 'updatedBy' => Auth::id()
                    ];
                    $patient = PatientMedicalHistory::where('udid', $medicalHistoryId)->update($input);
                    $getPatient = PatientMedicalHistory::where('udid', $medicalHistoryId)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if (!$medicalHistoryId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'history' => $request->input('history'), 'patientId' => $patientId,  'createdBy' => 1, 'udid' => $udid
                    ];
                    $patient = PatientMedicalHistory::create($input);
                    $getPatient = PatientMedicalHistory::where('id', $patient->id)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'history' => $request->input('history'), 'updatedBy' => 1
                    ];
                    $patient = PatientMedicalHistory::where('udid', $medicalHistoryId)->update($input);
                    $getPatient = PatientMedicalHistory::where('udid', $medicalHistoryId)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            }

            DB::commit();
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
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if ($medicalHistoryId) {
                    $getPatient = PatientMedicalHistory::where('udid', $medicalHistoryId)->with('patient')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                } else {
                    $getPatient = PatientMedicalHistory::where('patientId', $patientId)->with('patient')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                }
            } else {
                if ($medicalHistoryId) {
                    $getPatient = PatientMedicalHistory::where('udid', $medicalHistoryId)->with('patient')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                } else {
                    $patient = Patient::where('udid', $id)->first();
                    $patientId = $patient->id;
                    $getPatient = PatientMedicalHistory::where('patientId', $id)->with('patient')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient History
    public function patientMedicalHistoryDelete($request, $id, $medicalHistoryId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientMedicalHistory::where('udid', $medicalHistoryId)->update($data);
                PatientMedicalHistory::where('udid', $medicalHistoryId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientMedicalHistory::where('udid', $medicalHistoryId)->update($data);
                PatientMedicalHistory::where('udid', $medicalHistoryId)->delete();
            }

            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Medical Routine
    public function patientMedicalRoutineCreate($request, $id, $medicalRoutineId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$medicalRoutineId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'createdBy' => Auth::id(),
                        'startDate' => $request->input('startDate'), 'endDate' => $request->input('endDate'), 'patientId' => $patientId, 'udid' => $udid
                    ];
                    $patient = PatientMedicalRoutine::create($input);
                    $getPatient = PatientMedicalRoutine::where('id', $patient->id)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'updatedBy' => Auth::id(),
                        'startDate' => $request->input('startDate'), 'endDate' => $request->input('endDate')
                    ];
                    $patient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->update($input);
                    $getPatient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                if (!$medicalRoutineId) {
                    $udid = Str::uuid()->toString();
                    $input = [
                        'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'createdBy' => 1,
                        'startDate' => $request->input('startDate'), 'endDate' => $request->input('endDate'), 'patientId' => $patientId, 'udid' => $udid
                    ];
                    $patient = PatientMedicalRoutine::create($input);
                    $getPatient = PatientMedicalRoutine::where('id', $patient->id)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                } else {
                    $input = [
                        'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'updatedBy' => 1,
                        'startDate' => $request->input('startDate'), 'endDate' => $request->input('endDate')
                    ];
                    $patient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->update($input);
                    $getPatient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                    $message = ['message' => 'updated successfully'];
                }
            }
            DB::commit();
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
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if ($medicalRoutineId) {
                    $getPatient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->with('patient')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                } else {
                    $getPatient = PatientMedicalRoutine::where('patientId', $patientId)->with('patient')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                }
            } else {
                if ($medicalRoutineId) {
                    $getPatient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->with('patient')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                } else {
                    $patient = Patient::where('udid', $id)->first();
                    $patientId = $patient->id;
                    $getPatient = PatientMedicalRoutine::where('patientId', $patientId)->with('patient')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Medical Routine 
    public function patientMedicalRoutineDelete($request, $id, $medicalRoutineId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientMedicalRoutine::where('udid', $medicalRoutineId)->update($data);
                PatientMedicalRoutine::where('udid', $medicalRoutineId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientMedicalRoutine::where('udid', $medicalRoutineId)->update($data);
                PatientMedicalRoutine::where('udid', $medicalRoutineId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add And Update Patient Insurance
    public function patientInsuranceCreate($request, $id, $insuranceId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                PatientInsurance::where('patientId', $patientId)->delete();
                $insurance = $request->input('insurance');
                foreach ($insurance as $value) {
                    $input = [
                        'insuranceNumber' => $value['insuranceNumber'], 'expirationDate' => $value['expirationDate'],  'createdBy' => Auth::id(),
                        'insuranceNameId' => $value['insuranceName'], 'insuranceTypeId' => $value['insuranceType'], 'patientId' => $patientId, 'udid' => Str::uuid()->toString()
                    ];
                    $patient = PatientInsurance::create($input);
                    $getPatient = PatientInsurance::where('patientId', $patientId)->with('patient')->get();
                    $userdata = fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                }
            } else {
                $patient = Patient::where('udid', $id)->first();
                $patientId = $patient->id;
                PatientInsurance::where('patientId', $patientId)->delete();
                $udid = Str::uuid()->toString();
                $insurance = $request->input('insurance');
                foreach ($insurance as $value) {
                    $input = [
                        'insuranceNumber' => $value['insuranceNumber'], 'expirationDate' => $value['expirationDate'],  'createdBy' => 1,
                        'insuranceNameId' => $value['insuranceName'], 'insuranceTypeId' => $value['insuranceType'], 'patientId' => $patientId, 'udid' => $udid
                    ];
                    $patient = PatientInsurance::create($input);
                    $getPatient = PatientInsurance::where('patientId', $id)->with('patient')->get();
                    $userdata = fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                    $message = ['message' => 'created successfully'];
                }
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Insurance
    public function patientInsuranceList($request, $id, $insuranceId)
    {
        try {
            if (!$id) {
                if ($insuranceId) {
                    $getPatient = PatientInsurance::where('udid', $insuranceId)->with('patient', 'insuranceName', 'insuranceType')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                } else {
                    $user = User::where('id', Auth::id())->first();
                    $userId = $user->id;
                    $patient = Patient::where('userId', $userId)->first();
                    $patientId = $patient->id;
                    $getPatient = PatientInsurance::where('patientId', $patientId)->with('patient', 'insuranceName', 'insuranceType')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                }
            } else {
                if ($insuranceId) {
                    $getPatient = PatientInsurance::where('udid', $insuranceId)->with('patient', 'insuranceName', 'insuranceType')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                } else {
                    $patient = Patient::where('udid', $id)->first();
                    $patientId = $patient->id;
                    $getPatient = PatientInsurance::where('patientId', $patientId)->with('patient', 'insuranceName', 'insuranceType')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient Insurance
    public function patientInsuranceDelete($request, $id, $insuranceId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
                PatientInsurance::where('udid', $insuranceId)->update($data);
                PatientInsurance::where('udid', $insuranceId)->delete();
            } else {
                $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
                PatientInsurance::where('udid', $insuranceId)->update($data);
                PatientInsurance::where('udid', $insuranceId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Inventory With Login
    public function patientInventoryListing($request)
    {
        try {
            $patient = Patient::where('userId', Auth::id())->first();
            $patientId = $patient->id;
            $getPatient = PatientInventory::where('patientId', $patientId)->with('patient', 'inventory', 'deviceTypes')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Update Patient Inventory IsAdded
    public function inventoryUpdate($request, $id)
    {
        DB::beginTransaction();
        try {
            $inventory = ['isAdded' => 1, 'updatedBy' => Auth::id()];
            PatientInventory::where('id', $id)->update($inventory);
            $getPatient = PatientInventory::where('id', $id)->with('patient', 'inventory', 'deviceTypes')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            $message = ['message' => 'updated successfully'];
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Device
    public function patientDeviceCreate($request, $id, $deviceId)
    {
        DB::beginTransaction();
        try {
            if (!$deviceId) {
                $udid = Str::uuid()->toString();
                $device = [
                    'otherDeviceId' => $request->input('otherDevice'), 'status' => $request->status, 'udid' => $udid, 'patientId' => $id,
                    'createdBy' => 1
                ];
                $patient = PatientDevice::create($device);
                $getPatient = PatientDevice::where('id', $patient->id)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
                $message = ['message' => 'create successfully'];
            } else {
                $device = ['otherDeviceId' => $request->input('otherDevice'), 'status' => $request->input('status'), 'updatedBy' => 1];
                $patient = PatientDevice::where('udid', $deviceId)->update($device);
                $getPatient = PatientDevice::where('udid', $deviceId)->with('patient', 'otherDevice')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
                $message = ['message' => 'updated successfully'];
            }


            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Device
    public function patientDeviceList($request, $id)
    {
        try {
            $patient = Patient::where('udid', $id)->first();
            $patientId = $patient->id;
            $getPatient = PatientDevice::where('patientId', $patientId)->with('patient')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
