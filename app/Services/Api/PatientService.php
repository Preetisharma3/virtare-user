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
use App\Library\ErrorLogGenerator;
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
                $flag = ['udid' => Str::uuid()->toString(), 'createdBy' => Auth::id(), 'patientId' => $newData->id, 'flagId' => 4];
                PatientFlag::create($flag);
                $timeLine = [
                    'patientId' => $newData->id, 'heading' => 'Patient Register', 'title' => $newData->firstName . ' ' . $newData->lastName . ' ' . 'Added to platform', 'type' => 1,
                    'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);

                //Added Family in patientFamilyMember Table
                if (!empty($request->input('familyEmail'))) {
                    $userData = User::where([['email', $request->input('familyEmail')], ['roleId', 6]])->first();
                    if ($userData) {
                        $userEmail = $userData->id;
                        $familyMember = [
                            'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                            'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                            'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $newData->id, 'vital' => 1,
                            'messages' => 1,
                            'createdBy' => Auth::id(), 'userId' => $userEmail, 'udid' => Str::uuid()->toString(), 'isPrimary' => 1
                        ];
                        PatientFamilyMember::create($familyMember);
                    } else {
                        // Added family in user Table
                        $familyMemberUser = [
                            'password' => Hash::make('password'), 'udid' => Str::uuid()->toString(), 'email' => $request->input('familyEmail'),
                            'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 6
                        ];
                        $fam = User::create($familyMemberUser);
                        //Added Family in patientFamilyMember Table
                        $familyMember = [
                            'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                            'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                            'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $newData->id,
                            'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => Str::uuid()->toString(), 'vital' => 1,
                            'messages' => 1,
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
                $newData = Patient::where('udid', $id)->update($patient);
                // Updated family in user Table
                if ($request->input('familyMemberId')) {
                    $userData = User::where([['email', $request->input('familyEmail')], ['roleId', 6]])->first();
                    if ($userData) {
                        //Updated Family in patientFamilyMember Table
                        $familyMember = [
                            'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                            'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                            'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'),'userId'=>$userData->id,
                            'updatedBy' => Auth::id(), 'vital' => $request->input('vitalAuthorization'),
                            'messages' => $request->input('messageAuthorization'),
                        ];
                        PatientFamilyMember::where('id', $usersId->id)->update($familyMember);
                    } else {                    
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
                        PatientFamilyMember::where('id', $usersId->id)->update($familyMember);
                    }
                } else {
                    if (!empty($request->input('familyEmail'))) {
                        $userData = User::where([['email', $request->input('familyEmail')], ['roleId', 6]])->first();
                        if ($userData) {
                            $userEmail = $userData->id;
                            $familyMember = [
                                'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                                'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                                'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $id,
                                'createdBy' => Auth::id(), 'userId' => $userEmail, 'udid' => Str::uuid()->toString(), 'isPrimary' => 1, 'vital' => 1,
                                'messages' => 1,
                            ];
                            PatientFamilyMember::create($familyMember);
                        } else {
                            // Added family in user Table
                            $familyMemberUser = [
                                'password' => Hash::make('password'), 'udid' => Str::uuid()->toString(), 'email' => $request->input('familyEmail'),
                                'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 6
                            ];
                            $fam = User::create($familyMemberUser);
                            //Added Family in patientFamilyMember Table
                            $familyMember = [
                                'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('familyPhoneNumber'),
                                'contactTypeId' => json_encode($request->input('familyContactType')), 'contactTimeId' => $request->input('familyContactTime'),
                                'genderId' => $request->input('familyGender'), 'relationId' => $request->input('relation'), 'patientId' => $id,
                                'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => Str::uuid()->toString(), 'vital' => 1,
                                'messages' => 1,
                            ];
                            if (!empty($familyMember)) {
                                PatientFamilyMember::create($familyMember);
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
                    PatientEmergencyContact::where('udid', $request->emergencyId)->update($emergencyContact);
                } else {
                    if (!empty($request->input('emergencyEmail'))) {
                        $emergencyContact = [
                            'fullName' => $request->input('emergencyFullName'), 'phoneNumber' => $request->input('emergencyPhoneNumber'), 'contactTypeId' => json_encode($request->input('emergencyContactType')),
                            'contactTimeId' => $request->input('emergencyContactTime'), 'genderId' => $request->input('emergencyGender'), 'patientId' => $id,
                            'createdBy' => Auth::id(), 'email' => $request->input('emergencyEmail'), 'sameAsFamily' => $request->input('sameAsFamily'), 'udid' => Str::uuid()->toString()
                        ];
                        PatientEmergencyContact::create($emergencyContact);
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
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Patient Listing
    public function patientList($request, $id)
    {
        try {
            $roleId = auth()->user()->roleId;
            if ($id) {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    if ($roleId == 3) {
                        $staff = Patient::where('id', $patient)->whereHas('patientStaff', function ($query) use ($patient) {
                            $query->where('patientId', $patient);
                        })->first();
                        if (!empty($staff)) {
                            return fractal()->item($staff)->transformWith(new PatientTransformer())->toArray();
                        }
                    } elseif ($roleId == 6) {
                        $family = Patient::where('id', $patient)->whereHas('family', function ($query) use ($patient) {
                            $query->where('patientId', $patient);
                        })->first();
                        if (!empty($family)) {
                            return fractal()->item($family)->transformWith(new PatientTransformer())->toArray();
                        }
                    } elseif ($roleId == 1) {
                        $patient = Patient::where('id', $patient)->first();
                        if (!empty($patient)) {
                            return fractal()->item($patient)->transformWith(new PatientTransformer())->toArray();
                        }
                    } elseif ($roleId == 4) {
                        $patient = Patient::where('id', $patient)->first();
                        return fractal()->item($patient)->transformWith(new PatientTransformer())->toArray();
                    }
                } elseif ($roleId == 4) {
                    $patient = Patient::where('id', auth()->user()->patient->id)->first();
                    return fractal()->item($patient)->transformWith(new PatientTransformer())->toArray();
                }
            } else {
                if ($roleId == 3) {
                    if ($request->all) {
                        $staff = Patient::whereHas('patientStaff', function ($query) use ($request) {
                            $query->where('staffId', auth()->user()->staff->id)->whereHas('patient', function ($q) use ($request) {
                                $q->where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastName', 'LIKE', '%' . $request->search . '%');
                            });
                        })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->get();
                        if (!empty($staff)) {
                            return fractal()->collection($staff)->transformWith(new PatientTransformer())->toArray();
                        }
                    } else {
                        $staff = Patient::whereHas('patientStaff', function ($query) use ($request) {
                            $query->where('staffId', auth()->user()->staff->id)->whereHas('patient', function ($q) use ($request) {
                                $q->where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastName', 'LIKE', '%' . $request->search . '%');
                            });
                        })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->paginate(env('PER_PAGE', 20));
                        if (!empty($staff)) {
                            return fractal()->collection($staff)->transformWith(new PatientTransformer())->paginateWith(new IlluminatePaginatorAdapter($staff))->toArray();
                        }
                    }
                } elseif ($roleId == 6) {
                    if ($request->all) {
                        $family = Patient::whereHas('family', function ($query) use ($request) {
                            $query->where('id', auth()->user()->familyMember->id)->whereHas('patient', function ($q) use ($request) {
                                $q->where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastName', 'LIKE', '%' . $request->search . '%');
                            });
                        })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->get();
                        if (!empty($family)) {
                            return fractal()->collection($family)->transformWith(new PatientTransformer())->toArray();
                        }
                    } else {
                        $family = Patient::whereHas('family', function ($query) use ($request) {
                            $query->where('id', auth()->user()->familyMember->id)->whereHas('patient', function ($q) use ($request) {
                                $q->where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastName', 'LIKE', '%' . $request->search . '%');
                            });
                        })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->paginate(env('PER_PAGE', 20));
                        if (!empty($family)) {
                            return fractal()->collection($family)->transformWith(new PatientTransformer())->paginateWith(new IlluminatePaginatorAdapter($family))->toArray();
                        }
                    }
                } elseif ($roleId == 1) {
                    if ($request->all) {
                        $patient = Patient::where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastName', 'LIKE', '%' . $request->search . '%')->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->get();
                        return fractal()->collection($patient)->transformWith(new PatientTransformer())->toArray();
                    } else {
                        $patient = Patient::where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastName', 'LIKE', '%' . $request->search . '%')->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->paginate(env('PER_PAGE', 20));
                        return fractal()->collection($patient)->transformWith(new PatientTransformer())->paginateWith(new IlluminatePaginatorAdapter($patient))->toArray();
                    }
                } elseif ($roleId == 4) {
                    $patient = Patient::where('id', auth()->user()->patient->id)->first();
                    return fractal()->item($patient)->transformWith(new PatientTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
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
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Condition
    public function patientConditionCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $patient = Helper::entity('patient', $id);
            PatientCondition::where('patientId', $patient)->delete();
            $conditions = $request->input('condition');
            foreach ($conditions as $condition) {
                $input = [
                    'conditionId' => $condition,
                    'patientId' => $patient, 'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id()
                ];
                PatientCondition::create($input);
                $getPatient = PatientCondition::where('patientId', $patient)->with('patient')->get();
                $userdata = fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
                $message = ['message' => 'create successfully'];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient condition
    public function patientConditionList($request, $id, $conditionId)
    {
        try {
            if ($conditionId) {
                $getPatient = PatientCondition::where('udid', $conditionId)->with('patient', 'condition')->first();
                return fractal()->item($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientCondition::where('patientId', $patient)->with('patient', 'condition')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientConditionTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Referals
    public function patientReferalsCreate($request, $id, $referalsId)
    {
        DB::beginTransaction();
        try {
            if (!$referalsId) {
                $udid = Str::uuid()->toString();
                $patient = Helper::entity('patient', $id);
                $input = [
                    'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                    'patientId' => $patient, 'fax' => $request->input('fax'), 'createdBy' => Auth::id(), 'phoneNumber' => $request->input('phoneNumber'), 'udid' => $udid
                ];
                $patientData = PatientReferal::create($input);
                $getPatient = PatientReferal::where('id', $patientData->id)->with('patient', 'designation')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'name' => $request->input('name'), 'designationId' => $request->input('designation'), 'email' => $request->input('email'),
                    'fax' => $request->input('fax'), 'updatedBy' => Auth::id(), 'phoneNumber' => $request->input('phoneNumber')
                ];
                $patient = PatientReferal::where('udid', $referalsId)->update($input);
                $getPatient = PatientReferal::where('udid', $referalsId)->with('patient', 'designation')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Referals
    public function patientReferalsList($request, $id, $referalsId)
    {
        try {
            if ($referalsId) {
                $getPatient = PatientReferal::where('udid', $referalsId)->with('patient', 'designation')->first();
                return fractal()->item($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientReferal::where('patientId', $patient)->with('patient', 'designation')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientReferalTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Delete Patient Referals
    public function patientReferalsDelete($request, $id, $referalsId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientReferal::where('udid', $referalsId)->update($data);
            PatientReferal::where('udid', $referalsId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
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
                    'email' => $request->input('email'), 'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 5, 'udid' => $udid
                ];
                $userData = User::create($user);
                $patient = Helper::entity('patient', $id);
                $input = [
                    'sameAsReferal' => $request->input('sameAsAbove'), 'patientId' => $patient, 'fax' => $request->input('fax'),
                    'createdBy' => Auth::id(), 'phoneNumber' => $request->input('phoneNumber'), 'userId' => $userData->id, 'designationId' => $request->input('designation'),
                    'name' => $request->input('name'), 'udid' => $udid
                ];
                $patientData = PatientPhysician::create($input);
                $getPatient = PatientPhysician::where('id', $patientData->id)->with('patient', 'designation', 'user')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
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
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Physician
    public function patientPhysicianList($request, $id, $physicianId)
    {
        try {
            if ($physicianId) {
                $getPatient = PatientPhysician::where('udid', $physicianId)->with('patient', 'designation', 'user')->first();
                return fractal()->item($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientPhysician::where('patientId', $patient)->with('patient', 'designation', 'user')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientPhysicianTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Delete Patient Physician
    public function patientPhysicianDelete($request, $id, $physicianId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientPhysician::where('udid', $physicianId)->update($data);
            PatientPhysician::where('udid', $physicianId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Program
    public function patientProgramCreate($request, $id, $programId)
    {
        DB::beginTransaction();
        try {
            if (!$programId) {
                $patient = Helper::entity('patient', $id);
                $onboardingScheduleDate = Helper::date($request->input('onboardingScheduleDate'));
                $dischargeDate = Helper::date($request->input('dischargeDate'));
                $input = [
                    'programtId' => $request->input('program'), 'onboardingScheduleDate' => $onboardingScheduleDate, 'dischargeDate' => $dischargeDate,
                    'patientId' => $patient, 'createdBy' => Auth::id(), 'isActive' => $request->input('status'), 'udid' => Str::uuid()->toString()
                ];
                $patientData = PatientProgram::create($input);
                $getPatient = PatientProgram::where('id', $patientData->id)->with('patient', 'program')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $onboardingScheduleDate = Helper::date($request->input('onboardingScheduleDate'));
                $dischargeDate = Helper::date($request->input('dischargeDate'));
                $input = [
                    'programtId' => $request->input('program'), 'onboardingScheduleDate' => $onboardingScheduleDate, 'dischargeDate' => $dischargeDate,
                    'updatedBy' => Auth::id(), 'isActive' => $request->input('status')
                ];
                $patient = PatientProgram::where('udid', $programId)->update($input);
                $getPatient = PatientProgram::where('udid', $programId)->with('patient', 'program')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Program
    public function patientProgramList($request, $id, $programId)
    {
        try {
            if ($programId) {
                $getPatient = PatientProgram::where('udid', $programId)->with('patient', 'program')->first();
                return fractal()->item($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientProgram::where('patientId', $patient)->with('patient', 'program')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientProgramTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Delete Patient Program
    public function patientProgramDelete($request, $id, $programId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1];
            PatientProgram::where('udid', $programId)->update($data);
            PatientProgram::where('udid', $programId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Inventory
    public function patientInventoryCreate($request, $id, $inventoryId)
    {
        DB::beginTransaction();
        try {
            if (!$inventoryId) {
               $patientData = Patient::where('udid', $id)->first();
                $deviceType = Inventory::where('id',$request->input('inventory'))->with('model')->first();
                $deviceAssigned = PatientInventory::where('patientId', $patientData->id)->join('inventories','inventories.id','=','patientInventories.inventoryId')->join('deviceModels','deviceModels.id', '=',  'inventories.deviceModelId')->where('deviceModels.deviceTypeId',$deviceType->model['deviceTypeId'])->first();
                if (!$deviceAssigned) {
                    
                    $input = [
                        'inventoryId' => $request->input('inventory'), 'patientId' => $patientData->id, 'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                    ];
                    $patient = PatientInventory::create($input);
                    $inventory = Inventory::where('id', $patient->inventoryId)->first();
                    
                    Inventory::where('id', $patient->inventoryId)->update(array('isAvailable'=>0));

                    $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
                    $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
                    $deviceType = $device->name;
                    $timeLine = [
                        'patientId' => $patientData->id, 'heading' => 'Device Assigned', 'title' => $deviceType . ' ' . ' Device Assigned to ' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                        'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                    ];
                    PatientTimeLine::create($timeLine);
                    $getPatient = PatientInventory::where('id', $patient->id)->with('patient', 'inventory', 'deviceTypes')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                    $message = ['message' => trans('messages.createdSuccesfully')];
                } else {

                    return response()->json(['message' => 'Device Already Assigned to Patient'],409);
                }
            } else {
                $input = [
                    'isActive' => $request->input('status'),  'updatedBy' => Auth::id(),
                ];
                $patient = PatientInventory::where('udid', $inventoryId)->update($input);
                $getPatient = PatientInventory::where('udid', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Inventory
    public function patientInventoryList($request, $id, $inventoryId)
    {
        try {
            if ($inventoryId) {
                $getPatient = PatientInventory::where('udid', $inventoryId)->with('patient', 'inventory', 'deviceTypes')->first();
                return fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientInventory::where('patientId', $patient)->with('patient', 'inventory', 'deviceTypes')->latest()->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
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


            Inventory::where('id', $patient->inventoryId)->update(array('isAvailable'=>1));

            $deviceModel = DeviceModel::where('id', $inventory->deviceModelId)->first();
            $device = GlobalCode::where('id', $deviceModel->deviceTypeId)->first();
            $deviceType = $device->name;
            $timeLine = [
                'patientId' => $patientData->id, 'heading' => 'Device Removed', 'title' => $deviceType . ' ' . ' Device Removed from ' . ' ' . $patientData->firstName . ' ' . $patientData->lastName, 'type' => 1,
                'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
            ];
            PatientTimeLine::create($timeLine);
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientInventory::where('udid', $inventoryId)->update($data);
            PatientInventory::where('udid', $inventoryId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Vitals
    public function patientVitalCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            if ($id) {
                $dataVital = $request->vital;
                foreach ($dataVital as $vital) {
                    $vitalRecord = array();
                    if (!empty($vital['startTime'])) {
                        $vitalRecord['startTime'] = Helper::date($vital['startTime']);
                    };
                    if (!empty($vital['deviceType'])) {
                        $vitalRecord['deviceTypeId'] = $vital['deviceType'];
                    };
                    if (!empty($vital['units'])) {
                        $vitalRecord['units'] = $vital['units'];
                    }
                    if (!empty($vital['endTime'])) {
                        $vitalRecord['endTime'] = Helper::date($vital['endTime']);
                    }
                    if (!empty($id)) {
                        $vitalRecord['patientId'] = Helper::entity('patient', $id);
                    }
                    if (!empty($vital['takeTime'])) {
                        $vitalRecord['takeTime'] = Helper::date($vital['takeTime']);
                    }
                    if (!empty($vital['addType'])) {
                        $vitalRecord['addType'] = $vital['addType'];
                    }
                    if (!empty($vital['value'])) {
                        $vitalRecord['value'] = $vital['value'];
                    }
                    if (!empty($vital['createdType'])) {
                        $vitalRecord['createdType'] = $vital['createdType'];
                    }
                    if (!empty($vital['deviceInfo'])) {
                        $vitalRecord['deviceInfo'] = json_encode($vital['deviceInfo']);
                    }
                    if (!empty($vital['type'])) {
                        $vitalRecord['vitalFieldId'] = $vital['type'];
                    }
                    $vitalRecord['createdBy'] = Auth::id();
                    $vitalRecord['udid'] = Str::uuid()->toString();
                    $vitalData = PatientVital::create($vitalRecord);
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
                $dataVital = $request->vital;
                foreach ($dataVital as $vital) {
                    $takeTime = Helper::date($vital['takeTime']);
                    $startTime = Helper::date($vital['startTime']);
                    $endTime = Helper::date($vital['endTime']);
                    $data = [
                        'vitalFieldId' => $vital['type'],
                        'deviceTypeId' => $vital['deviceType'],
                        'createdBy' => Auth::id(),
                        'udid' => Str::uuid()->toString(),
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
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Vitals
    public function patientVitalList($request, $id)
    {
        try {
            if ($id) {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $patient]])->get();
                    if ($familyMember == true) {
                        $patientIdx = $patient;
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
                    $patientIdx = $patient;
                }
                $data = DB::select(
                    'CALL getPatientVital("' . $patientIdx . '","' . $fromDate . '","' . $toDate . '","' . $type . '","' . $deviceType . '")',
                );
                return fractal()->collection($data)->transformWith(new PatientVitalTransformer())->toArray();
            } else {
                $patient = auth()->user()->patient->id;
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $patient]])->get();
                    if ($familyMember == true) {
                        $patientIdx = $patient;
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
                    $patientIdx = $patient;
                }
                $data = DB::select(
                    'CALL getPatientVital("' . $patientIdx . '","' . $fromDate . '","' . $toDate . '","' . $type . '","' . $deviceType . '")',
                );
                return fractal()->collection($data)->transformWith(new PatientVitalTransformer())->toArray();
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function vitalList($request, $id)
    {
        if (empty($patientIdx)) {
            $patientIdx = auth()->user()->patient->id;
        } elseif (!empty($patientIdx)) {
            $patient = Helper::entity('patient', $id);
            $patientIdx = $patient;
        }
        $notAccess = Helper::haveAccess($patientIdx);
        if (!$notAccess) {
            $result = DB::select(
                "CALL getVitals('" . $patientIdx . "','" . $request->type . "')"
            );
            return fractal()->collection($result)->transformWith(new PatientVitalTransformer())->toArray();
        }
    }

    public function latest($request, $id, $vitalType)
    {
        if (!$id) {
            $patientId = auth()->user()->patient->id;
        } elseif ($id) {
            $patient = Helper::entity('patient', $id);
            $patientId = $patient;
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
        if ($request->deviceType) {
            $data = PatientVital::where([['patientId', $patientId], ['deviceTypeId', $request->deviceType]])->orderBy('takeTime', 'desc')->get()->unique('vitalFieldId');
        } else {
            $data = PatientVital::where('patientId', $patientId)->orderBy('takeTime', 'desc')->get()->unique('vitalFieldId');
        }
        return fractal()->collection($data)->transformWith(new PatientVitalTransformer())->toArray();
    }

    // Delete Patient Vitals
    public function patientVitalDelete($request, $id, $vitalId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientVital::find($vitalId)->update($data);
            PatientVital::find($vitalId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Clinical Data
    public function patientMedicalHistoryCreate($request, $id, $medicalHistoryId)
    {
        DB::beginTransaction();
        try {
            if (!$medicalHistoryId) {
                $udid = Str::uuid()->toString();
                $patient = Helper::entity('patient', $id);
                $input = [
                    'history' => $request->input('history'), 'patientId' => $patient,  'createdBy' => Auth::id(), 'udid' => $udid
                ];
                $patient = PatientMedicalHistory::create($input);
                $getPatient = PatientMedicalHistory::where('id', $patient->id)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'history' => $request->input('history'), 'updatedBy' => 1
                ];
                $patient = PatientMedicalHistory::where('udid', $medicalHistoryId)->update($input);
                $getPatient = PatientMedicalHistory::where('udid', $medicalHistoryId)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Medical History
    public function patientMedicalHistoryList($request, $id, $medicalHistoryId)
    {
        try {
            if ($medicalHistoryId) {
                $getPatient = PatientMedicalHistory::where('udid', $medicalHistoryId)->with('patient')->first();
                return fractal()->item($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientMedicalHistory::where('patientId', $patient)->with('patient')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientMedicalTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Delete Patient History
    public function patientMedicalHistoryDelete($request, $id, $medicalHistoryId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientMedicalHistory::where('udid', $medicalHistoryId)->update($data);
            PatientMedicalHistory::where('udid', $medicalHistoryId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Medical Routine
    public function patientMedicalRoutineCreate($request, $id, $medicalRoutineId)
    {
        DB::beginTransaction();
        try {
            $startDate = Helper::date($request->input('startDate'));
            $endDate = Helper::date($request->input('endDate'));
            if (!$medicalRoutineId) {
                $udid = Str::uuid()->toString();
                $patient = Helper::entity('patient', $id);

                $input = [
                    'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'createdBy' => Auth::id(),
                    'startDate' => $startDate, 'endDate' => $endDate, 'patientId' => $patient, 'udid' => $udid
                ];
                $patient = PatientMedicalRoutine::create($input);
                $getPatient = PatientMedicalRoutine::where('id', $patient->id)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $input = [
                    'medicine' => $request->input('medicine'), 'frequency' => $request->input('frequency'),  'updatedBy' => Auth::id(),
                    'startDate' => $startDate, 'endDate' => $endDate
                ];
                $patient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->update($input);
                $getPatient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->with('patient')->first();
                $userdata = fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                $message = ['message' => trans('messages.updatedSuccesfully')];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Medical Routine 
    public function patientMedicalRoutineList($request, $id, $medicalRoutineId)
    {
        try {
            if ($medicalRoutineId) {
                $getPatient = PatientMedicalRoutine::where('udid', $medicalRoutineId)->with('patient')->first();
                return fractal()->item($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientMedicalRoutine::where('patientId', $patient)->with('patient')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientMedicalRoutineTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Delete Patient Medical Routine 
    public function patientMedicalRoutineDelete($request, $id, $medicalRoutineId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientMedicalRoutine::where('udid', $medicalRoutineId)->update($data);
            PatientMedicalRoutine::where('udid', $medicalRoutineId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Add And Update Patient Insurance
    public function patientInsuranceCreate($request, $id, $insuranceId)
    {
        DB::beginTransaction();
        try {
            $patient = Helper::entity('patient', $id);
            PatientInsurance::where('patientId', $patient)->delete();
            $insurance = $request->input('insurance');
            foreach ($insurance as $value) {
                $input = [
                    'insuranceNumber' => $value['insuranceNumber'], 'expirationDate' => $value['expirationDate'],  'createdBy' => Auth::id(),
                    'insuranceNameId' => $value['insuranceName'], 'insuranceTypeId' => $value['insuranceType'], 'patientId' => $patient, 'udid' => Str::uuid()->toString()
                ];
                PatientInsurance::create($input);
                $getPatient = PatientInsurance::where('patientId', $patient)->with('patient')->get();
                $userdata = fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                $message = ['message' => 'create successfully'];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Insurance
    public function patientInsuranceList($request, $id, $insuranceId)
    {
        try {
            if ($insuranceId) {
                $getPatient = PatientInsurance::where('udid', $insuranceId)->with('patient', 'insuranceName', 'insuranceType')->first();
                return fractal()->item($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientInsurance::where('patientId', $patient)->with('patient', 'insuranceName', 'insuranceType')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientInsuranceTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Delete Patient Insurance
    public function patientInsuranceDelete($request, $id, $insuranceId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientInsurance::where('udid', $insuranceId)->update($data);
            PatientInsurance::where('udid', $insuranceId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Inventory With Login
    public function patientInventoryListing($request)
    {
        try {
            $patient = Patient::where('userId', Auth::id())->first();
            $patientId = $patient->id;
            $notAccess = Helper::haveAccess($patientId);
            if (!$notAccess) {
                $getPatient = PatientInventory::where('patientId', $patientId)->with('patient', 'inventory', 'deviceTypes')->where('isActive', '1')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Update Patient Inventory IsAdded
    public function inventoryUpdate($request, $id)
    {
        DB::beginTransaction();
        try {
            $inventory = ['isAdded' => 1];
            PatientInventory::where('udid', $id)->update($inventory);
            $patient = PatientInventory::where('udid', $id)->first();
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
                    'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
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
                    'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);
                $patient = ['isDeviceAdded' => 1];
                Patient::where('id', $patientData->id)->update($patient);
            }
            $getPatient = PatientInventory::where('udid', $id)->with('patient', 'inventory', 'deviceTypes')->first();
            $userdata = fractal()->item($getPatient)->transformWith(new PatientInventoryTransformer())->toArray();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
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
                    $patient = PatientDevice::where('udid', $deviceId)->update($device);
                    $getPatient = PatientDevice::where('udid', $deviceId)->with('patient', 'otherDevice')->first();
                    $userdata = fractal()->item($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
                    $message = ['message' => trans('messages.updatedSuccesfully')];
                }
            } else {
                if (!$deviceId) {
                    $udid = Str::uuid()->toString();
                    $device = [
                        'otherDeviceId' => $request->input('otherDevice'), 'status' => $request->status, 'udid' => $udid, 'patientId' => $id,
                        'createdBy' => Auth::id()
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
                    $message = ['message' => trans('messages.updatedSuccesfully')];
                }
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Device
    public function patientDeviceList($request, $id)
    {
        try {
            if (!$id) {
                $patient = Patient::where('userId', Auth::id())->first();
                $patientId = $patient->id;
                $getPatient = PatientDevice::where('patientId', $patientId)->with('patient')->get();
            } else {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientDevice::where('patientId', $patient)->with('patient')->get();
                }
            }
            return fractal()->collection($getPatient)->transformWith(new PatientDeviceTransformer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient Timeline
    public function patientTimelineList($request, $id)
    {
        try {
            $patient = Helper::entity('patient', $id);
            $notAccess = Helper::haveAccess($patient);
            if (!$notAccess) {
                $getPatient = PatientTimeLine::where('patientId', $patient)->with('patient')->orderBy('id', 'DESC')->get();
                return fractal()->collection($getPatient)->transformWith(new PatientTimelineTransformer())->toArray();
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
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
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // List Patient TimeLog
    public function patientFlagList($request, $id, $flagId)
    {
        try {
            if (!$flagId) {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $getPatient = PatientFlag::where('patientId', $patient)->with('flag')->get();
                    return fractal()->collection($getPatient)->transformWith(new PatientFlagTransformer())->toArray();
                }
            } else {
                $getPatient = PatientFlag::where('udid', $flagId)->with('flag')->first();
                return fractal()->item($getPatient)->transformWith(new PatientFlagTransformer())->toArray();
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }
}
