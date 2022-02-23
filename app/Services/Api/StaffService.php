<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Carbon\Carbon;
use App\Models\User\User;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\UserRole\UserRole;
use Illuminate\Support\Facades\DB;
use App\Models\Patient\PatientStaff;
use App\Models\Appointment\Appointment;
use App\Models\StaffContact\StaffContact;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Staff\StaffRoleTransformer;
use App\Models\Staff\StaffProvider\StaffProvider;
use App\Models\StaffAvailability\StaffAvailability;
use App\Transformers\Staff\StaffContactTransformer;
use App\Transformers\Staff\StaffPatientTransformer;
use App\Transformers\Staff\StaffProviderTransformer;
use App\Transformers\Patient\PatientCountTransformer;
use App\Transformers\Staff\StaffAppointmentTransformer;
use App\Transformers\Appointment\AppointmentTransformer;
use App\Transformers\Staff\StaffAvailabilityTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Appointment\AppointmentDataTransformer;
use App\Transformers\Appointment\AppointmentListTransformer;

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
                'createdBy' => 1,
                'roleId' => 3,
            ];
            $data = User::create($user);
            $staff = [
                'udid' => Str::random(10),
                'userId' => $data->id,
                'email' => $data->email,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'phoneNumber' => $request->phoneNumber,
                'genderId' => $request->genderId,
                'specializationId' => $request->specializationId,
                'designationId' => $request->designationId,
                'networkId' => $request->networkId,
                'roleId' => 3,
                'createdBy' => 1
            ];
            $newData = Staff::create($staff);
            $staffData = Staff::where('id', $newData->id)->first();
            $message = ["message" => "created Successfully"];
            $resp =  fractal()->item($staffData)->transformWith(new StaffTransformer())->toArray();
            $endData = array_merge($message, $resp);
            Helper::updateFreeswitchUser();
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listStaff($request, $id)
    {
        if(!$id){
            $data = Staff::with('roles', 'appointment')->paginate(5);
            return fractal()->collection($data)->transformWith(new StaffTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        }else{
            $data = Staff::where('udid',$id)->with('roles', 'appointment')->first();
            return fractal()->item($data)->transformWith(new StaffTransformer())->toArray();
        }
    }

    public function updateStaff($request, $id)
    {
        $staffId = Staff::where('udid', $id)->first();
        $uId = $staffId->userId;

        $user = [
            'email' => $request->input('email'),
            'updatedBy' => 1
        ];
        User::where('id', $uId)->update($user);
        $staff = [
            'email' => $user['email'],
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'phoneNumber' => $request->phoneNumber,
            'genderId' => $request->genderId,
            'specializationId' => $request->specializationId,
            'designationId' => $request->designationId,
            'networkId' => $request->networkId,
            'providerId' => $request->providerId,
            'roleId' => 3,
            'createdBy' => 1
        ];
        Staff::where('udid', $id)->update($staff);
        $staffData = Staff::where('udid', $id)->first();
        $message = ["message" => "Updated Successfully"];
        $resp =  fractal()->item($staffData)->transformWith(new StaffTransformer())->toArray();
        $endData = array_merge($message, $resp);
        return $endData;
    }


    public function addStaffContact($request, $id)
    {
        try {
            if (!empty($request->id)) {
                $staff = Staff::where('udid', $id)->first();
                $udid = Str::random(10);
                $firstName = $request->firstName;
                $lastName = $request->lastName;
                $email = $request->email;
                $phoneNumber = $request->phoneNumber;
                $staffId = $staff->id;
                DB::select('CALL createStaffContact("' . $udid . '","' . $firstName . '","' . $lastName . '","' . $email . '","' . $phoneNumber . '","' . $staffId . '")');
                $staffContactData = StaffContact::where('udid', $udid)->first();
                $message = ["message" => "created Successfully"];
                $resp =  fractal()->item($staffContactData)->transformWith(new StaffContactTransformer())->toArray();
                $endData = array_merge($message, $resp);
                return $endData;
            } else {
                return response()->json(['message' => 'Somethings Went Worng']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listStaffContact($request, $id)
    {
        try {
            if (!empty($request->id)) {
                $staff = Staff::where('udid', $id)->first();
                $staffContact = StaffContact::where('staffId', $staff->id)->get();
                return  fractal()->collection($staffContact)->transformWith(new StaffContactTransformer())->toArray();
            } else {
                return response()->json(['message' => 'Somethings Went Worng']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateStaffContact($request, $staffId, $id)
    {
        try {
            $staffContact = [
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'phoneNumber' => $request->input('phoneNumber'),
            ];
            $staff = Staff::where('udid', $staffId)->first();
            StaffContact::where([['staffId', $staff->id], ['udid', $id]])->update($staffContact);
            $staffContactData = StaffContact::where('udid', $id)->first();
            $message = ["message" => "Updated Successfully"];
            $resp =  fractal()->item($staffContactData)->transformWith(new StaffContactTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteStaffContact($request, $staffId, $id)
    {
        try {
            if (!empty($request->staffId)) {
                $staff = Staff::where('udid', $staffId)->first();
                $input = ['deletedBy' => 1, 'isActive' => 0, 'isDelete' => 1];
                StaffContact::where([['staffId', $staff->id], ['udid', $id]])->update($input);
                StaffContact::where([['staffId', $staff->id], ['udid', $id]])->delete();
                return response()->json(['message' => "Deleted Successfully"]);
            } else {
                return response()->json(['message' => 'Somethings Went Worng']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function addStaffAvailability($request, $id)
    {
        try {
            $udid = Str::random(10);
            $startTime = $request->startTime;
            $endTime = $request->endTime;
            $staff = Staff::where('udid', $id)->first();
            $staffId = $staff->id;
            DB::select('CALL createStaffAvailability("' . $udid . '","' . $startTime . '","' . $endTime . '","' . $staffId . '")');
            $staffAvailability = StaffAvailability::where('udid', $udid)->first();
            $message = ["message" => "created Successfully"];
            $resp =  fractal()->item($staffAvailability)->transformWith(new StaffAvailabilityTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listStaffAvailability($request, $id)
    {
        try {
            $staff = Staff::where('udid', $id)->first();
            $staffAvailability = StaffAvailability::where('staffId', $staff->id)->get();
            return fractal()->collection($staffAvailability)->transformWith(new StaffAvailabilityTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateStaffAvailability($request, $staffId, $id)
    {
        try {
            $staffAvailability = [
                'startTime' => $request->input('startTime'),
                'endTime' => $request->input('endTime'),
            ];
            $staff = Staff::where('udid', $staffId)->first();
            StaffAvailability::where([['staffId', $staff->id], ['udid', $id]])->update($staffAvailability);
            $staffAvailability = StaffAvailability::where('udid', $id)->first();
            $message = ["message" => "Updated Successfully"];
            $resp =  fractal()->item($staffAvailability)->transformWith(new StaffAvailabilityTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteStaffAvailability($request, $staffId, $id)
    {
        try {
            $staff = Staff::where('udid', $staffId)->first();
            $input = ['deletedBy' => 1, 'isActive' => 0, 'isDelete' => 1];
            StaffAvailability::where([['staffId', $staff->id], ['udid', $id]])->update($input);
            StaffAvailability::where([['staffId', $staff->id], ['udid', $id]])->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function addStaffRole($request, $id)
    {
        try {
            $roles = $request->roles;
            $staff = Staff::where('udid', $id)->first();
            foreach ($roles as $roleId) {
                $udid = Str::random(10);
                $staffId = $staff->id;
                $accessRoleId = $roleId;
                DB::select('CALL createstaffRole("' . $udid . '","' . $staffId . '","' . $accessRoleId . '")');
            }
            return response()->json(['message' => "created Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listStaffRole($request, $id)
    {
        try {
            $staff = Staff::where('udid', $id)->first();
            $staffRole = UserRole::where('staffId', $staff->id)->with('roles')->get();
            return fractal()->collection($staffRole)->transformWith(new StaffRoleTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateStaffRole($request, $staffId, $id)
    {
        try {
            $staffRole = [
                'userId' => $request->input('userId'),
                'roleId' => $request->input('roleId'),
            ];
            $staff = Staff::where('udid', $staffId)->first();
            UserRole::where([['staffId', $staff->id], ['udid', $id]])->update($staffRole);
            return response()->json(['message' => "Updated Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteStaffRole($request, $staffId, $id)
    {
        try {
            $staff = Staff::where('udid', $staffId)->first();
            $input = ['deletedBy' => 1, 'isActive' => 0, 'isDelete' => 1];
            UserRole::where([['staffId', $staff->id], ['udid', $id]])->update($input);
            UserRole::where([['staffId', $staff->id], ['udid', $id]])->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function addStaffProvider($request, $id)
    {
        try {
            $providers = $request->providers;
            $staff = Staff::where('udid', $id)->first();
            foreach ($providers as $providerId) {
                $udid = Str::random(10);
                $providerId = $providerId;
                $staffId  = $staff->id;
                DB::select('CALL createStaffProvider("' . $udid . '","' . $staffId . '","' . $providerId . '")');
            }
            return response()->json(['message' => "created Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listStaffProvider($request, $id)
    {
        try {
            $staff = Staff::where('udid', $id)->first();
            $staffProvider = StaffProvider::where('staffId', $staff->id)->with('providers')->get();
            return fractal()->collection($staffProvider)->transformWith(new StaffProviderTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateStaffProvider($request, $staffId, $id)
    {
        try {
            $providers = $request->providers;
            $staff = Staff::where('udid', $staffId)->first();
            foreach ($providers as $providerId) {
                $staffProvider = [
                    'providerId' => $providerId,
                    'staffId' => $staff->id,
                ];
                StaffProvider::where([['staffId', $staff->id], ['udid', $id]])->update($staffProvider);
            }
            return response()->json(['message' => "Updated Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteStaffProvider($request, $staffId, $id)
    {
        try {
            $staff = Staff::where('udid', $staffId)->first();
            $input = ['deletedBy' => 1, 'isActive' => 0, 'isDelete' => 1];
            StaffProvider::where([['staffId', $staff->id], ['udid', $id]])->update($input);
            StaffProvider::where([['staffId', $staff->id], ['udid', $id]])->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function specializationCount()
    {
        $data = DB::select(
            'CALL careCoordinatorSpecializationCount()',
        );
        return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

    public function networkCount()
    {
        $data = DB::select(
            'CALL careCoordinatorNetworkCount()',
        );
        return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

    public function patientList($id)
    {
        if ($id) {
            $staff = Staff::where('udid', $id)->first();
            $staffId = $staff->id;
        } else {
            $staffId = auth()->user()->staff->id;
        }
        $data = Patient::whereHas('patientStaff', function ($query) use ($staffId) {
            $query->where('staffId','=',$staffId);
        })->get();
        return fractal()->collection($data)->transformWith(new PatientTransformer())->toArray();
    }

    public function appointmentList($id)
    {
        if ($id) {
            $staff = Staff::where('udid', $id)->first();
            $staffId = $staff->id;
        } else {
            $staffId = auth()->user()->staff->id;
        }
        $data = Appointment::where([['staffId', $staffId], ['startDateTime', '>=', Carbon::today()]])->paginate(5);
        $results = Helper::dateGroup($data, 'startDateTime');
        return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
    }

    public function patientAppointment($id)
    {
        if ($id) {
            $patient = Patient::where('udid', $id)->first();
            $patientId = $patient->id;
        } else {
            $patientId = auth()->user()->patient->id;
        }
        $data = Appointment::where('patientId', $patientId)->whereDate('startDateTime', '=', Carbon::today())->paginate(5);
        $results = Helper::dateGroup($data, 'startDateTime');
        return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
    }


}
