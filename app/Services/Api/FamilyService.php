<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class FamilyService
{
    public function familyCreate($request, $id, $familyId)
    {
        DB::beginTransaction();
        try {
            if (!$familyId) {
                $patient = Patient::where('id', auth()->user()->patient->id)->first();
                $patientId = $patient->id;
                $userData = User::where([['email', $request->input('email')], ['roleId', 6]])->first();
                if ($userData) {
                    //Added Family in patientFamilyMember Table
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),
                        'contactTypeId' => $request->input('contactType'), 'contactTimeId' => $request->input('contactTime'),
                        'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'), 'patientId' => $patientId,
                        'createdBy' => Auth::id(), 'userId' => $userData->id, 'udid' => Str::uuid()->toString(),
                         'vital' => $request->input('vitalAuthorization'), 'messages' => $request->input('messageAuthorization'),'isPrimary'=>0
                    ];
                } else {
                    $familyMemberUser = [
                        'password' => Hash::make('password'), 'udid' => Str::uuid()->toString(), 'email' => $request->input('email'), 'profilePhoto' => str_replace(str_replace("public", "", URL::to('/') . '/'), "", $request->input('profilePhoto')),
                        'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 6
                    ];
                    $fam = User::create($familyMemberUser);
                    
                    //Added Family in patientFamilyMember Table
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),
                        'contactTypeId' => $request->input('contactType'), 'contactTimeId' => $request->input('contactTime'),
                        'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'), 'patientId' => $patientId,
                        'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => Str::uuid()->toString(),
                         'vital' => $request->input('vitalAuthorization'), 'messages' => $request->input('messageAuthorization'),'isPrimary'=>0
                    ];
                }
                $familyData = PatientFamilyMember::create($familyMember);
                $data = PatientFamilyMember::where('id', $familyData->id)->first();
                $userdata = fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $patient = PatientFamilyMember::where('udid', $familyId)->first();
                $usersId = $patient->userId;
                $familyMemberUser = array();
                if (!empty($request->input('profilePhoto'))) {
                    $familyMemberUser['profilePhoto'] = str_replace(str_replace("public", "", URL::to('/') . '/'), "", $request->input('profilePhoto'));
                }
                if (!empty($request->input('email'))) {
                    $familyMemberUser['email'] = $request->input('email');
                }
                $familyMemberUser['updatedBy'] = Auth::id();

                $userData = User::where([['email', $request->input('email')], ['roleId', 6]])->first();
                if($userData){
                    //updated Family in patientFamilyMember Table
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),'userId'=>$userData->id,
                        'contactTypeId' => $request->input('contactType'), 'contactTimeId' => $request->input('contactTime'),
                        'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'),
                        'updatedBy' => Auth::id(), 'vital' => $request->input('vitalAuthorization'), 
                        'messages' => $request->input('messageAuthorization'),
                    ];
                }else{
                    $fam = User::where('id', $usersId)->update($familyMemberUser);
                    //updated Family in patientFamilyMember Table
                    $familyMember = [
                        'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),
                        'contactTypeId' => $request->input('contactType'), 'contactTimeId' => $request->input('contactTime'),
                        'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'),
                        'updatedBy' => Auth::id(), 'vital' => $request->input('vitalAuthorization'), 
                        'messages' => $request->input('messageAuthorization'),
                    ];
                }
                $familyData = PatientFamilyMember::where('udid', $familyId)->update($familyMember);
                $data = PatientFamilyMember::where('udid', $familyId)->first();
                $userdata = fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer())->toArray();
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
}
