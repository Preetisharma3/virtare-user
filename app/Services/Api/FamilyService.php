<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class FamilyService
{
    public function familyCreate($request, $id,$familyId)
    { 
        DB::beginTransaction();
        try {
            if (!$familyId) {
                $user=User::where('udid',$id)->first();
                $usersId=Patient::where('userId',$user->id)->first();
                $patient = Patient::where('id', $usersId->id)->first();
                $patientId = $patient->id;
                $udid = Str::uuid()->toString();
                $familyMemberUser = [
                    'password' => Hash::make('password'), 'udid' => $udid, 'email' => $request->input('email'),
                    'emailVerify' => 1, 'createdBy' => Auth::id(), 'roleId' => 6
                ];
                $fam = User::create($familyMemberUser);

                //Added Family in patientFamilyMember Table
                $familyMember = [
                    'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),
                    'contactTypeId' => $request->input('contactType'), 'contactTimeId' => $request->input('contactTime'),
                    'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'), 'patientId' => $patientId,
                    'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => $udid,'vital'=>$request->input('vitalAuthorization'),'messages'=>$request->input('messageAuthorization')
                ];
                $familyData = PatientFamilyMember::create($familyMember);
                $data = PatientFamilyMember::where('id', $familyData->id)->first();

                $userdata = fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer())->toArray();
                $message = ['message' => trans('messages.createdSuccesfully')];
            } else {
                $patient = PatientFamilyMember::where('udid', $familyId)->first();
                $usersId = $patient->userId;
                $familyMemberUser = [
                    'email' => $request->input('email'),
                    'updatedBy' => Auth::id()
                ];
                $fam = User::where('id', $usersId)->update($familyMemberUser);
                //updated Family in patientFamilyMember Table
                $familyMember = [
                    'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),
                    'contactTypeId' => $request->input('contactType'), 'contactTimeId' => $request->input('contactTime'),
                    'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'),
                    'updatedBy' => Auth::id(),'vital'=>$request->input('vitalAuthorization'),'messages'=>$request->input('messageAuthorization'),
                ];
                $familyData = PatientFamilyMember::where('udid',$familyId)->update($familyMember);
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
