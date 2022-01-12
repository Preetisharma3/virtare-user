<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Transformers\User\UserTransformer;

class StaffService
{
    public function addStaff($request)
    {
        try {
            $user = [
                'udid' => Str::random(10),
                'email' => $request->email,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // 'password'
                'emailVerify' => 1,
                'createdBy' => Auth::id()
            ];
            $data = User::create($user);
            $staff = [
                'userId'=>$data->id,
                'email' => $data->email,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'phoneNumber' => $request->phoneNumber,
                'genderId' => $request->genderId,
                'specializationId' => $request->specializationId,
                'designationId' => $request->designationId,
                'networkId' => $request->networkId,
                'createdBy' => Auth::id()
            ];
            $newData = Staff::create($staff);
           return response()->json(['message' =>'Created Successfully'],200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
