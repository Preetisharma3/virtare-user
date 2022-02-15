<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\StaffService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffRequest;
use App\Models\Staff\Staff;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addStaff(StaffRequest $request)
    {
        return (new StaffService)->addStaff( $request);
        
    }
    public function listStaff(Request $request)
    {
        return (new StaffService)->listStaff($request);
    }

    public function updateStaff(Request $request, $id)
    {
        return (new StaffService)->updateStaff($request, $id); 
    }

    public function addStaffContact(Request $request , $id)
    {
        return (new StaffService)->addStaffContact($request, $id);
    }

    public function listStaffContact(Request $request, $id)
    {
        return (new StaffService)->listStaffContact($request, $id);
    }

    public function updateStaffContact(Request $request , $id, $staffId)
    {
        return (new StaffService)->updateStaffContact($request, $id, $staffId);
    }

    public function deleteStaffContact(Request $request, $id, $staffId)
    {
        return (new StaffService)->deleteStaffContact($request, $id, $staffId);  
    }

    public function addStaffAvailability(Request $request , $id)
    {
        return (new StaffService)->addStaffAvailability($request, $id);
    }

    public function listStaffAvailability(Request $request,$id)
    {
        return (new StaffService)->listStaffAvailability($request,$id);
    }

    public function updateStaffAvailability(Request $request , $id, $staffId)
    {
        return (new StaffService)->updateStaffAvailability($request, $id, $staffId); 
    }

    public function deleteStaffAvailability(Request $request, $id, $staffId)
    {
        return (new StaffService)->deleteStaffAvailability($request, $id, $staffId);  
    }

    public function addStaffRole(Request $request, $id)
    {
        return (new StaffService)->addStaffRole($request, $id);
    }

    public function listStaffRole(Request $request,$id)
    {
        return (new StaffService)->listStaffRole($request,$id);
    }

    public function updateStaffRole(Request $request , $id, $staffId)
    {
        return (new StaffService)->updateStaffRole($request, $id, $staffId); 
    }

    public function deleteStaffRole(Request $request ,$staffId, $id)
    {
        return (new StaffService)->deleteStaffRole($request,$staffId, $id); 
    }

    public function specializationCount()
    {
        return (new StaffService)->specializationCount(); 
    }

    public function networkCount()
    {
        return (new StaffService)->networkCount(); 
    }

}
