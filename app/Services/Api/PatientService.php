<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Tag\Tag;
use App\Models\Note\Note;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Vital\VitalField;
use App\Models\Document\Document;
use App\Models\Device\DeviceModel;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use App\Models\Patient\PatientFlag;
use App\Models\Patient\PatientVital;
use App\Models\Vital\VitalTypeField;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GlobalCode\GlobalCode;
use App\Models\Patient\PatientDevice;
use App\Models\Patient\PatientProgram;
use App\Models\Patient\PatientReferal;
use App\Models\Patient\PatientTimeLog;
use App\Models\Patient\PatientTimeLine;
use App\Models\Patient\PatientCondition;
use App\Models\Patient\PatientInsurance;
use App\Models\Patient\PatientInventory;
use App\Models\Patient\PatientPhysician;
use App\Models\Patient\PatientFamilyMember;
use App\Models\Patient\PatientMedicalHistory;
use App\Models\Patient\PatientMedicalRoutine;
use App\Models\Patient\PatientEmergencyContact;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Patient\PatientFlagTransformer;
use App\Transformers\Patient\PatientVitalTransformer;
use App\Transformers\Patient\PatientDeviceTransformer;
use App\Transformers\Patient\PatientMedicalTransformer;
use App\Transformers\Patient\PatientProgramTransformer;
use App\Transformers\Patient\PatientReferalTransformer;
use App\Transformers\Patient\PatientTimeLogTransformer;
use App\Transformers\Patient\PatientTimelineTransformer;
use App\Transformers\Patient\PatientConditionTransformer;
use App\Transformers\Patient\PatientInsuranceTransformer;
use App\Transformers\Patient\PatientInventoryTransformer;
use App\Transformers\Patient\PatientPhysicianTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Patient\PatientMedicalRoutineTransformer;

class PatientService
{
    // Add And Update  Patient 
    public function patientCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                // Added Ptient details in User Table
                $user = [
                    'password' => Hash::make('password'), 'email' => $request->input('email'), 'udid' => Str::uuid()->toString(),
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
                    'address' => $request->input('address'), 'createdBy' => 1, 'height' => $request->input('height'), 'weight' => $request->input('weight'), 'udid' => Str::uuid()->toString()
                ];
                $newData = Patient::create($patient);
                $timeLine = [
                    'patientId' => $newData->id, 'heading' => 'Patient Register', 'title' => $newData->firstName . ' ' . $newData->lastName . ' ' . 'Added to platform', 'type' => 1,
                    'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);

                //Added Family in patientFamilyMember Table
                if (!empty($request->input('familyEmail'))) {
                    $userData = User::where([['email', $request->input('familyEmail')], ['roleId', 4]])->first();
                    if ($userData) {
                        $userEmail = $userData->id;
                        $familyMember = [
                            'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                            'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                            'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $newData->id, 'vital' => $request->input('vitalAuthorization'),
                            'messages' => $request->input('messageAuthorization'),
                            'createdBy' => Auth::id(), 'userId' => $userEmail, 'udid' => Str::uuid()->toString(), 'isPrimary' => 1
                        ];
                        PatientFamilyMember::create($familyMember);
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
                            'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => Str::uuid()->toString(), 'vital' => $request->input('vitalAuthorization'),
                            'messages' => $request->input('messageAuthorization'),
                        ];
                        if (!empty($familyMember)) {
                            PatientFamilyMember::create($familyMember);
                        }
                    }
                }
                //Added emergency contact in PatientEmergencyContact table
                if (!empty($request->input('emergencyEmail'))) {
                    $emergencyContact = [
                        'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                        'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'), 'patientId' => $newData->id,
                        'createdBy' => Auth::id(), 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily'), 'udid' => Str::uuid()->toString()
                    ];
                    PatientEmergencyContact::create($emergencyContact);
                }
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
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $usersId = Patient::where('udid', $id)->first();
                $uId = $usersId->userId;

                // Updated Ptient details in User Table
                $user = [
                    'email' => $request->input('email'),
                    'updatedBy' => Auth::id()
                ];
                User::where('id', $uId)->update($user);

                // Updated  patient details in Patient Table
                $patient = [
                    'firstName' => $request->input('firstName'), 'middleName' => $request->input('middleName'), 'lastName' => $request->input('lastName'),
                    'dob' => $request->input('dob'), 'genderId' => $request->input('gender'), 'languageId' => $request->input('language'), 'otherLanguageId' => json_encode($request->input('otherLanguage')),
                    'nickName' => $request->input('nickName'), 'phoneNumber' => $request->input('phoneNumber'), 'contactTypeId' => json_encode($request->input('contactType')),
                    'contactTimeId' => $request->input('contactTime'), 'medicalRecordNumber' => $request->input('medicalRecordNumber'), 'countryId' => $request->input('country'),
                    'stateId' => $request->input('state'), 'city' => $request->input('city'), 'zipCode' => $request->input('zipCode'), 'appartment' => $request->input('appartment'),
                    'address' => $request->input('address'), 'updatedBy' => Auth::id(), 'height' => $request->input('height'), 'weight' => $request->input('weight')
                ];
                $newData = Patient::where('id', $id)->update($patient);
                // Updated family in user Table
                if ($request->input('familyMemberId')) {
                    $family = $request->input('familyMemberId');
                    $usersId = PatientFamilyMember::where('udid', $family)->first();
                    $familyId = $usersId->userId;
                    $familyMemberUser = [
                        'email' => $request->input('familyEmail'),
                        'updatedBy' => Auth::id()
                    ];
                    $fam = User::where('id', $familyId)->update($familyMemberUser);

                    //Updated Family in patientFamilyMember Table
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                        'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                        'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'),
                        'updatedBy' => Auth::id(), 'vital' => $request->input('vitalAuthorization'),
                        'messages' => $request->input('messageAuthorization'),
                    ];
                    PatientFamilyMember::where('id', $request->familyMemberId)->update($familyMember);
                } else {
                    if (!empty($request->input('familyEmail'))) {
                        $userData = User::where([['email', $request->input('familyEmail')], ['roleId', 4]])->first();
                        if ($userData) {
                            $userEmail = $userData->id;
                            $familyMember = [
                                'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                                'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                                'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $id,
                                'createdBy' => Auth::id(), 'userId' => $userEmail, 'udid' => Str::uuid()->toString(), 'isPrimary' => 1, 'vital' => $request->input('vitalAuthorization'),
                                'messages' => $request->input('messageAuthorization'),
                            ];
                            PatientFamilyMember::create($familyMember);
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
                                'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $id,
                                'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => Str::uuid()->toString(), 'vital' => $request->input('vitalAuthorization'),
                                'messages' => $request->input('messageAuthorization'),
                            ];
                            if (!empty($familyMember)) {
                                $patientFamily = PatientFamilyMember::create($familyMember);
                            }
                        }
                    }
                }

                //Updated emergency contact in PatientEmergencyContact table
                if ($request->input('emergencyId')) {
                    $emergencyContact = [
                        'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                        'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'),
                        'updatedBy' => Auth::id(), 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily')
                    ];
                    $emg = PatientEmergencyContact::where('id', $request->emergencyId)->update($emergencyContact);
                } else {
                    if (!empty($request->input('emergencyEmail'))) {
                        $emergencyContact = [
                            'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                            'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'), 'patientId' => $id,
                            'createdBy' => Auth::id(), 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily'), 'udid' => Str::uuid()->toString()
                        ];
                        $emergency = PatientEmergencyContact::create($emergencyContact);
                    }
                }
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
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            Helper::updateFreeswitchUser();
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
                )->paginate(env('PER_PAGE', 20));
                return fractal()->collection($getPatient)->transformWith(new PatientTransformer())->paginateWith(new IlluminatePaginatorAdapter($getPatient))->toArray();
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
            $patient = Patient::where('id', $id)->first();
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
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            PatientCondition::where('patientId', $id)->delete();
            $udid = Str::uuid()->toString();
            $conditions = $request->input('condition');
            foreach ($conditions as $condition) {
                $input = [
                    'conditionId' => $condition,
                    'patientId' => $id, 'udid' => $udid, 'createdBy' => 1
                ];
                PatientCondition::create($input);
                $getPatient = PatientCondition::where('patientId', $id)->with('patient')->get();
                $userdata = fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
                $message = ['message' => 'create successfully'];
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

    // Add And Update Patient Referals
    public function patientReferalsCreate($request, $id, $referalsId)
    {
        DB::beginTransaction();
        try {
            if (!$referalsId) {
                $udid = Str::uuid()->toString();
                $input = [
                    'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                    'patientId' => $id, 'fax' => $request->input('fax'), 'createdBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'udid' => $udid
                ];
                $patient = PatientReferal::create($input);
                $getPatient = PatientReferal::where('id', $patient->id)->with('patient', 'designation')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                    'fax' => $request->input('fax'), 'updatedBy' => 1, 'phoneNumber' => $request->input('phoneNumber')
                ];
                $patient = PatientReferal::where('id', $referalsId)->update($input);
                $getPatient = PatientReferal::where('id', $referalsId)->with('patient', 'designation')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
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

    // Delete Patient Referals
    public function patientReferalsDelete($request, $id, $referalsId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            PatientReferal::find($referalsId)->update($data);
            PatientReferal::find($referalsId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            if (!$physicianId) {
                $udid = Str::uuid()->toString();
                $user = [
                    'password' => Hash::make('password'),
                    'email' => $request->input('email'), 'emailVerify' => 1, 'createdBy' => 1, 'roleId' => 5, 'udid' => $udid
                ];
                $userData = User::create($user);
                $input = [
                    'sameAsReferal' => $request->input('sameAsAbove'), 'patientId' => $id, 'fax' => $request->input('fax'),
                    'createdBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'userId' => $userData->id, 'designationId' => $request->input('designation'),
                    'name' => $request->input('name'), 'udid' => $udid
                ];
                $patient = PatientPhysician::create($input);
                $getPatient = PatientPhysician::where('id', $patient->id)->with('patient', 'designation', 'user')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $usersId = PatientPhysician::where('id', $physicianId)->first();
                $uId = $usersId->userId;
                $user = [
                    'email' => $request->input('email'), 'updatedBy' => 1,
                ];
                $userData = User::where('id', $uId)->update($user);
                $input = [
                    'sameAsReferal' => $request->input('sameAsAbove'), 'patientId' => $id, 'fax' => $request->input('fax'),
                    'updatedBy' => 1, 'phoneNumber' => $request->input('phoneNumber'), 'designationId' => $request->input('designation'),
                    'name' => $request->input('name'),
                ];
                $patient = PatientPhysician::where('id', $physicianId)->update($input);
                $getPatient = PatientPhysician::where('id', $physicianId)->with('patient', 'designation', 'user')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
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

    // Delete Patient Physician
    public function patientPhysicianDelete($request, $id, $physicianId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            PatientPhysician::find($physicianId)->update($data);
            PatientPhysician::find($physicianId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            if (!$programId) {
                $udid = Str::uuid()->toString();
                $input = [
                    'programtId' => $request->input('program'), 'onboardingScheduleDate' =>  date("Y-m-d", $request->input('onboardingScheduleDate')), 'dischargeDate' => date("Y-m-d", $request->input('dischargeDate')),
                    'patientId' => $id, 'createdBy' => 1, 'isActive' => $request->input('status'), 'udid' => $udid
                ];
                $patient = PatientProgram::create($input);
                $getPatient = PatientProgram::where('id', $patient->id)->with('patient', 'program')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'programtId' => $request->input('program'), 'onboardingScheduleDate' => date("Y-m-d", $request->input('onboardingScheduleDate')), 'dischargeDate' => date("Y-m-d", $request->input('dischargeDate')),
                    'updatedBy' => 1, 'isActive' => $request->input('status')
                ];
                $patient = PatientProgram::where('id', $programId)->update($input);
                $getPatient = PatientProgram::where('id', $programId)->with('patient', 'program')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
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

    // Delete Patient Program
    public function patientProgramDelete($request, $id, $programId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1];
            $program = PatientProgram::where('id', $programId)->update($data);
            $patient = PatientProgram::where('id', $programId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            if (!$inventoryId) {
                $udid = Str::uuid()->toString();
                $patientData = Patient::where('udid', $id)->first();
                $input = [
                    'inventoryId' => $request->input('inventory'), 'patientId' => $patientData->id, 'createdBy' => 1, 'udid' => $udid
                ];
                $patient = PatientInventory::create($input);
                $inventory = Inventory::where('id', $patient->inventoryId)->first();
                $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
                $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
                $deviceType = $device->name;
                $timeLine = [
                    'patientId' => $patientData->id, 'heading' => 'Device Assigned', 'title' => $deviceType . ' ' . ' Device Assigned to ' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                    'createdBy' => 1, 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);
                $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceTypes')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'isActive' => $request->input('status'),  'updatedBy' => 1,
                ];
                $patient = PatientInventory::where('id', $inventoryId)->update($input);
                $getPatient = PatientInventory::where('id', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
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
            $data = Patient::where('udid', $id)->first();
                    if ($inventoryId) {
                        $getPatient = PatientInventory::where('id', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                        return fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                    } else {
                        $getPatient = PatientInventory::where('patientId', $data->id)->with('patient', 'inventory', 'deviceTypes')->latest()->get();
                        return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
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
            $patient = PatientInventory::where('udid', $inventoryId)->first();
            $patientData = Patient::where('udid', $id)->first();
            $inventory = Inventory::where('id', $patient->inventoryId)->first();
            $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
            $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
            $deviceType = $device->name;
            $timeLine = [
                'patientId' => $patientData->id, 'heading' => 'Device Removed', 'title' => $deviceType . ' ' . ' Device Removed from ' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                'createdBy' => 1, 'udid' => Str::uuid()->toString()
            ];
            PatientTimeLine::create($timeLine);
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            PatientInventory::where('udid',$inventoryId)->update($data);
            PatientInventory::where('udid',$inventoryId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
                    $patient = Helper::entity('patient',$id);
                    $data = [
                        'vitalFieldId' => $vital['type'],
                        'deviceTypeId' => $vital['deviceType'],
                        'createdBy' => Auth::id(),
                        'udid' => $udid,
                        'value' => $vital['value'],
                        'patientId' => $patient,
                        'units' => $vital['units'],
                        'takeTime' => $takeTime,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                        'addType' => $vital['addType'],
                        'createdType' => $vital['createdType'],
                        'deviceInfo' => json_encode($vital['deviceInfo'])
                    ];
                    $vitalData = PatientVital::create($data);
                    $note = ['createdBy' => Auth::id(), 'note' => $vital['comment'], 'udid' => Str::uuid()->toString(), 'entityType' => 'patientVital', 'referenceId' => $vitalData->id];
                    Note::create($note);
                    $result = PatientVital::where('id', $vitalData->id)->first();
                    $patientData = Patient::where('udid', $id)->first();
                    $vitalField = VitalField::where('id', $vitalData->vitalFieldId)->first();
                    $type = VitalTypeField::where('vitalFieldId', $vitalData->vitalFieldId)->first();
                    $device = GlobalCode::where('id', $type->vitalTypeId)->first();
                    $timeLine = [
                        'patientId' => $patientData->id, 'heading' => 'Vital Update', 'title' => $patientData->firstName . ' ' . $patientData->lastName . ' ' .
                            'Submit' . ' ' . $device->name . ' ' . 'Reading' . ' ' . $vitalField->name . ' ' . $vital['value'], 'type' => 1,
                        'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                    ];
                    PatientTimeLine::create($timeLine);
                }
            } else {
                $patient = Patient::where('userId', Auth::user()->id)->first();
                $patientId = $patient->id;
                $udid = Str::uuid()->toString();
                $dataVital = $request->vital;
                foreach ($dataVital as $vital) {
                    $takeTime = Helper::date($vital['takeTime']);
                    $startTime = Helper::date($vital['startTime']);
                    $endTime = Helper::date($vital['endTime']);
                    $data = [
                        'vitalFieldId' => $vital['type'],
                        'deviceTypeId' => $vital['deviceType'],
                        'createdBy' => Auth::id(),
                        'udid' => $udid,
                        'value' => $vital['value'],
                        'patientId' => $patientId,
                        'units' => $vital['units'],
                        'takeTime' => $takeTime,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                        'addType' => $vital['addType'],
                        'createdType' => $vital['createdType'],
                        'deviceInfo' => json_encode($vital['deviceInfo'])
                    ];
                    $vitalData = PatientVital::create($data);
                    $note = ['createdBy' => Auth::id(), 'note' => $vital['comment'], 'udid' => Str::uuid()->toString(), 'entityType' => 'patientVital', 'referenceId' => $vitalData->id];
                    Note::create($note);
                    $result = PatientVital::where('id', $vitalData->id)->first();
                    $patientData = Patient::where('id', $patientId)->first();
                    $vitalField = VitalField::where('id', $vitalData->vitalFieldId)->first();
                    $type = VitalTypeField::where('vitalFieldId', $vitalData->vitalFieldId)->first();
                    $device = GlobalCode::where('id', $type->vitalTypeId)->first();
                    $timeLine = [
                        'patientId' => $patientData->id, 'heading' => 'Vital Update', 'title' => $patientData->firstName . ' ' . $patientData->lastName . ' ' .
                            'Submit' . ' ' . $device->name . ' ' . 'Reading' . ' ' . $vitalField->name . ',' . $vital['value'], 'type' => 1,
                        'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                    ];
                    PatientTimeLine::create($timeLine);
                }
            }
            $userdata = fractal()->item($result)->transformWith(new PatientVitalTransformer())->toArray();
            $message = ['message' => trans('messages.createdSuccesfully')];
            DB::commit();
            // $endData = array_merge($message, $userdata);
            return $message;
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
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $id]])->get();
                if ($familyMember == true) {
                    $patientIdx = $id;
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
            } elseif (!$id) {
                $patientIdx = '';
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
            $type = '';
            $fromDate = '';
            $toDate = '';
            $deviceType = '';
            if (!empty($request->toDate)) {
                $toDate = date("Y-m-d H:i:s", $request->toDate);
            }
            if (!empty($request->fromDate)) {
                $fromDate = date("Y-m-d H:i:s", $request->fromDate);
            }
            if (!empty($request->type)) {
                $type = $request->type;
            }
            if (!empty($request->deviceType)) {
                $deviceType = $request->deviceType;
            }
            if (empty($patientIdx)) {
                $patientIdx = auth()->user()->patient->id;
            } elseif (!empty($patientIdx)) {
                $patientIdx = $id;
            }
            $data = DB::select(
                'CALL getPatientVital("' . $patientIdx . '","' . $fromDate . '","' . $toDate . '","' . $type . '","' . $deviceType . '")',
            );
            return fractal()->collection($data)->transformWith(new PatientVitalTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function vitalList($request, $id)
    {
        if (empty($patientIdx)) {
            $patientIdx = auth()->user()->patient->id;
        } elseif (!empty($patientIdx)) {
            $patientIdx = $id;
        }
        $result = DB::select(
            "CALL getVitals('" . $patientIdx . "','" . $request->type . "')"
        );
        return fractal()->collection($result)->transformWith(new PatientVitalTransformer())->toArray();
    }

    public function latest($request, $id, $vitalType)
    {
        if (!$id) {
            $patientId = auth()->user()->patient->id;
        } elseif ($id) {
            $patientId = $id;
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
        $data = PatientVital::where([['patientId', $patientId],['deviceTypeId',$request->deviceType]])->orderBy('takeTime', 'desc')->get();
        return fractal()->collection($data)->transformWith(new PatientVitalTransformer())->toArray();
    }

    // Delete Patient Vitals
    public function patientVitalDelete($request, $id, $vitalId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            PatientVital::find($vitalId)->update($data);
            PatientVital::find($vitalId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            if (!$medicalHistoryId) {
                $udid = Str::uuid()->toString();
                $input = [
                    'history' => $request->input('history'), 'patientId' => $id,  'createdBy' => 1, 'udid' => $udid
                ];
                $patient = PatientMedicalHistory::create($input);
                $getPatient = PatientMedicalHistory::where('id', $patient->id)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'history' => $request->input('history'), 'updatedBy' => 1
                ];
                $patient = PatientMedicalHistory::where('id', $medicalHistoryId)->update($input);
                $getPatient = PatientMedicalHistory::where('id', $medicalHistoryId)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
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

    // Delete Patient History
    public function patientMedicalHistoryDelete($request, $id, $medicalHistoryId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientMedicalHistory::where('udid',$medicalHistoryId)->update($data);
            PatientMedicalHistory::where('udid',$medicalHistoryId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            if (!$medicalRoutineId) {
                $udid = Str::uuid()->toString();
                $input = [
                    'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'createdBy' => 1,
                    'startDate' => date("Y-m-d", $request->input('startDate')), 'endDate' => date("Y-m-d", $request->input('endDate')), 'patientId' => $id, 'udid' => $udid
                ];
                $patient = PatientMedicalRoutine::create($input);
                $getPatient = PatientMedicalRoutine::where('id', $patient->id)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'updatedBy' => 1,
                    'startDate' => $request->input('startDate'), 'endDate' => $request->input('endDate')
                ];
                $patient = PatientMedicalRoutine::where('id', $medicalRoutineId)->update($input);
                $getPatient = PatientMedicalRoutine::where('id', $medicalRoutineId)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
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

    // Delete Patient Medical Routine 
    public function patientMedicalRoutineDelete($request, $id, $medicalRoutineId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            PatientMedicalRoutine::where('udid',$medicalRoutineId)->update($data);
            PatientMedicalRoutine::where('udid',$medicalRoutineId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            PatientInsurance::where('patientId', $id)->delete();
            $udid = Str::uuid()->toString();
            $insurance = $request->input('insurance');
            foreach ($insurance as $value) {
                $input = [
                    'insuranceNumber' => $value['insuranceNumber'], 'expirationDate' => $value['expirationDate'],  'createdBy' => 1,
                    'insuranceNameId' => $value['insuranceName'], 'insuranceTypeId' => $value['insuranceType'], 'patientId' => $id, 'udid' => $udid
                ];
                $patient = PatientInsurance::create($input);
                $getPatient = PatientInsurance::where('patientId', $id)->with('patient')->get();
                $userdata = fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                $message = ['message' => 'create successfully'];
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
            if ($insuranceId) {
                $getPatient = PatientInsurance::where('id', $insuranceId)->with('patient', 'insuranceName', 'insuranceType')->first();
                return fractal()->item($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
            } else {
                $getPatient = PatientInsurance::where('patientId', $id)->with('patient', 'insuranceName', 'insuranceType')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
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
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            PatientInsurance::find($insuranceId)->update($data);
            PatientInsurance::find($insuranceId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
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
            $inventory = ['isAdded' => 1];
            PatientInventory::where('id', $id)->update($inventory);
            $patient = PatientInventory::where('id', $id)->first();
            $user = User::where('id', Auth::id())->first();
            $userId = $user->roleId;
            if ($userId == 4) {
                $patientData = Patient::where('userId', $user->id)->first();
                $inventory = Inventory::where('id', $patient->inventoryId)->first();
                $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
                $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
                $deviceType = $device->name;
                $timeLine = [
                    'patientId' => $patientData->id, 'heading' => 'Inventory Assigned', 'title' => $deviceType . ' ' . 'Linked to' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                    'createdBy' => 1, 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);
                $patient = ['isDeviceAdded' => 1];
                Patient::where('id', $patientData->id)->update($patient);
            } elseif ($userId == 3) {
                $patientData = PatientInventory::where('id', $id)->first();
                $inventory = Inventory::where('id', $patient->inventoryId)->first();
                $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
                $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
                $deviceType = $device->name;
                $timeLine = [
                    'patientId' => $patientData->patientId, 'heading' => 'Inventory Assigned', 'title' => $deviceType . ' ' . 'Linked to' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                    'createdBy' => 1, 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);
                $patient = ['isDeviceAdded' => 1];
                Patient::where('id', $patientData->id)->update($patient);
            }
            $getPatient = PatientInventory::where('id', $id)->with('patient', 'inventory', 'deviceTypes')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            $message = ['message' => trans('messages.updatedSuccesfully')];
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
            if (!$id) {
                $userId = Auth::id();
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                if (!$deviceId) {
                    $udid = Str::uuid()->toString();
                    $device = [
                        'otherDeviceId' => $request->input('otherDevice'), 'status' => $request->status, 'udid' => $udid, 'patientId' => $patientId,
                        'createdBy' => Auth::id()
                    ];
                    $patient = PatientDevice::create($device);
                    $getPatient = PatientDevice::where('id', $patient->id)->with('patient')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
                    $message = ['message' => 'create successfully'];
                } else {
                    $device = ['otherDeviceId' => $request->input('otherDevice'), 'status' => $request->input('status'), 'updatedBy' => Auth::id()];
                    $patient = PatientDevice::where('id', $deviceId)->update($device);
                    $getPatient = PatientDevice::where('id', $deviceId)->with('patient', 'otherDevice')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
                    $message = ['message' => trans('messages.updatedSuccesfully')];
                }
            } else {
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
                    $patient = PatientDevice::where('id', $deviceId)->update($device);
                    $getPatient = PatientDevice::where('id', $deviceId)->with('patient', 'otherDevice')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
                    $message = ['message' => trans('messages.updatedSuccesfully')];
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

    // List Patient Device
    public function patientDeviceList($request, $id)
    {
        try {
            if (!$id) {
                $userId = Auth::id();
                $patient = Patient::where('userId', $userId)->first();
                $patientId = $patient->id;
                $getPatient = PatientDevice::where('patientId', $patientId)->with('patient')->get();
            } else {
                $getPatient = PatientDevice::where('patientId', $id)->with('patient')->get();
            }
            return fractal()->collection($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient Timeline
    public function patientTimelineList($request, $id)
    {
        try {
            $patient = Patient::where('udid', $id)->first();
            $patientId = $patient->id;
            $getPatient = PatientTimeLine::where('patientId', $patientId)->with('patient')->orderBy('id', 'DESC')->get();
            return fractal()->collection($getPatient)->transformWith(new PatientTimelineTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add TimeLog
    public function patientTimeLogAdd($request, $entityType, $id, $timelogId)
    {
        DB::beginTransaction();
        try {
            if (!$timelogId) {
                $dateConvert = Helper::date($request->input('date'));
                $timeConvert = Helper::time($request->input('timeAmount'));
                $patientId = Patient::where('udid', $id)->first();
                $performedBy = Helper::entity('staff', $request->input('performedBy'));
                $loggedBy = Helper::entity('staff', $request->input('loggedBy'));
                $input = [
                    'categoryId' => $request->input('category'), 'loggedId' => $loggedBy, 'udid' => Str::uuid()->toString(),
                    'performedId' => $performedBy, 'date' => $dateConvert, 'timeAmount' => $timeConvert,
                    'createdBy' => Auth::id(), 'patientId' => $patientId->id
                ];
                $data = PatientTimeLog::create($input);
                if ($request->input('note')) {
                    $note = [
                        'note' => $request->input('note'), 'entityType' => 'auditlog', 'referenceId' => $data->id, 'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id()
                    ];
                    Note::create($note);
                }
                $data = response()->json(['message' => trans('messages.createdSuccesfully')]);
            } else {
                $dateConvert = Helper::date($request->input('date'));
                $timeConvert = Helper::time($request->input('timeAmount'));
                $timeLog = array();
                if (!empty($request->category)) {
                    $timeLog['categoryId'] = $request->category;
                }
                if (!empty($request->loggedBy)) {
                    $loggedBy = Helper::entity('staff', $request->input('loggedBy'));
                    $timeLog['loggedId'] = $loggedBy;
                }
                if (!empty($request->performedBy)) {

                    $performedBy = Helper::entity('staff', $request->input('performedBy'));
                    $timeLog['performedId'] = $performedBy;
                }
                if (!empty($request->date)) {
                    $timeLog['date'] = $dateConvert;
                }
                if (!empty($request->timeAmount)) {
                    $timeLog['timeAmount'] = $timeConvert;
                }
                $timeLog['updatedBy'] = Auth::id();
                $data = PatientTimeLog::where('udid', $timelogId)->update($timeLog);
                if ($request->input('noteId')) {
                    $noteData = ['note' => $request->input('note'), 'updatedBy' => Auth::id()];
                    Note::where('id', $request->input('noteId'))->update($noteData);
                } else {
                    $time = PatientTimeLog::where('udid', $timelogId)->first();

                    $noteData = [
                        'note' => $request->input('note'), 'entityType' => $request->input('entityType'), 'referenceId' => $time->id,
                        'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id(),
                    ];
                    $note = Note::create($noteData);
                }
                $data = response()->json(['message' => trans('messages.updatedSuccesfully')]);
            }
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient TimeLog
    public function patientTimeLogList($request, $entity, $id, $timelogId)
    {
        try {
            if ($request->latest) {
                $patientId = Patient::where('udid', $id)->first();
                $timeLog = PatientTimeLog::where('patientId', $patientId->id)->with('category', 'logged', 'performed', 'notes')->latest()->get();
                return fractal()->collection($timeLog)->transformWith(new PatientTimeLogTransformer())->toArray();
            } else {
                if (!$timelogId) {
                    if ($id) {
                        $patientId = Patient::where('udid', $id)->first();
                        $getPatient = PatientTimeLog::where('patientId', $patientId->id)->with('category', 'logged', 'performed', 'notes')->get();
                        return fractal()->collection($getPatient)->transformWith(new PatientTimeLogTransformer())->toArray();
                    } else {
                        $getPatient = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->get();
                        return fractal()->collection($getPatient)->transformWith(new PatientTimeLogTransformer())->toArray();
                    }
                } else {
                    $getPatient = PatientTimeLog::where('udid', $timelogId)->with('category', 'logged', 'performed', 'notes')->first();
                    return fractal()->item($getPatient)->transformWith(new PatientTimeLogTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient TimeLog
    public function patientTimeLogDelete($request, $entity, $id, $timelogId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientTimeLog::where('udid', $timelogId)->update($data);
            PatientTimeLog::where('udid', $timelogId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Add Patient Flags
    public function patientFlagAdd($request, $id)
    {
        DB::beginTransaction();
        try {
            $patientId = Patient::where('udid', $id)->first();
            $udid = Str::uuid()->toString();
            $input = ['udid' => $udid, 'patientId' => $patientId->id, 'flagId' => $request->input('flag'), 'icon' => $request->input('icon')];
            $flags = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
            PatientFlag::where('patientId', $patientId)->update($flags);
            PatientFlag::where('patientId', $patientId)->delete();
            $flag = PatientFlag::create($input);
            $data = PatientFlag::where('id', $flag->id)->first();
            $userdata = fractal()->item($data)->transformWith(new PatientFlagTransformer())->toArray();
            $message = ['message' => trans('messages.createdSuccesfully')];
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Patient TimeLog
    public function patientFlagList($request, $id, $flagId)
    {
        try {
            if (!$flagId) {
                $patientId = Patient::where('udid', $id)->first();
                $getPatient = PatientFlag::where('patientId', $patientId->id)->with('flag')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientFlagTransformer())->toArray();
            } else {
                $getPatient = PatientFlag::where('udid', $flagId)->with('flag')->first();
                return fractal()->item($getPatient)->transformWith(new PatientFlagTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    // staff setup device

    public function staffInventory($request, $id, $staffId)
    {
        DB::beginTransaction();
        try {
            if ($staffId) {
                $inventory = ['isAdded' => 1];
                PatientInventory::where('id', $id)->update($inventory);
                $patient = PatientInventory::where('id', $id)->first();
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patientData = Patient::where('userId', $userId)->first();
                $inventory = Inventory::where('id', $patient->inventoryId)->first();
                $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
                $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
                $deviceType = $device->name;
                $timeLine = [
                    'patientId' => $patientData->id, 'heading' => 'Inventory Assigned', 'title' => $deviceType . ' ' . 'Linked to' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                    'createdBy' => 1, 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);

                $patient = ['isDeviceAdded' => 1];
                Patient::where('id', $patientData->id)->update($patient);

                $getPatient = PatientInventory::where('id', $id)->with('patient', 'inventory', 'deviceTypes')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
                DB::commit();
                $endData = array_merge($message, $userdata);
                return $endData;
            } else {
                $inventory = ['isAdded' => 1];
                PatientInventory::where('id', $id)->update($inventory);
                $patient = PatientInventory::where('id', $id)->first();
                $user = User::where('id', Auth::id())->first();
                $userId = $user->id;
                $patientData = Patient::where('userId', $userId)->first();
                $inventory = Inventory::where('id', $patient->inventoryId)->first();
                $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
                $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
                $deviceType = $device->name;
                $timeLine = [
                    'patientId' => $patientData->id, 'heading' => 'Inventory Assigned', 'title' => $deviceType . ' ' . 'Linked to' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                    'createdBy' => 1, 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);

                $patient = ['isDeviceAdded' => 1];
                Patient::where('id', $patientData->id)->update($patient);

                $getPatient = PatientInventory::where('id', $id)->with('patient', 'inventory', 'deviceTypes')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
                DB::commit();
                $endData = array_merge($message, $userdata);
                return $endData;
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
