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
    public function familyCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $patient = Patient::where('userId', Auth::id())->first();
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
                    'contactTypeId' => json_encode($request->input('contactType')), 'contactTimeId' => json_encode($request->input('contactTime')),
                    'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'), 'patientId' => $patientId,
                    'createdBy' => Auth::id(), 'userId' => $fam->id, 'udid' => $udid
                ];
                $familyData = PatientFamilyMember::create($familyMember);
                $data = PatientFamilyMember::where('id', $familyData->id)->first();
                $userdata = fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer())->toArray();
                $message = ['message' => 'created successfully'];
            } else {
                $patient = PatientFamilyMember::where('userId', Auth::id())->first();
                $usersId = $patient->userId;
                $familyMemberUser = [
                    'email' => $request->input('email'),
                    'updatedBy' => Auth::id()
                ];
                $fam = User::where('id', $usersId)->update($familyMemberUser);

                //updated Family in patientFamilyMember Table
                $familyMember = [
                    'fullName' => $request->input('fullName'), 'phoneNumber' => $request->input('phoneNumber'),
                    'contactTypeId' => json_encode($request->input('contactType')), 'contactTimeId' => $request->input('contactTime'),
                    'genderId' => $request->input('gender'), 'relationId' => $request->input('relation'),
                    'updatedBy' => Auth::id(),
                ];
                $familyData = PatientFamilyMember::where('id', $id)->update($familyMember);
                $data = PatientFamilyMember::where('id', $id)->first();
                $userdata = fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer())->toArray();
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
}
