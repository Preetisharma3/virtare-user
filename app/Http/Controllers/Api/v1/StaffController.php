<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\StaffService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffContactRequest;
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
    public function listStaff(Request $request,$id=null)
    {
        return (new StaffService)->listStaff($request,$id);
    }

    public function updateStaff(StaffRequest $request, $id)
    {
        return (new StaffService)->updateStaff($request, $id); 
    }

    public function addStaffContact(StaffContactRequest $request , $id)
    {
        return (new StaffService)->addStaffContact($request, $id);
        
    }

    public function listStaffContact(Request $request, $id)
    {
        return (new StaffService)->listStaffContact($request, $id);
    }

    public function updateStaffContact(Request $request , $staffId,$id)
    {
        return (new StaffService)->updateStaffContact($request, $staffId,$id);
    }

    public function deleteStaffContact(Request $request, $staffId,$id)
    {
        return (new StaffService)->deleteStaffContact($request, $staffId,$id);  
    }

    public function addStaffAvailability(Request $request , $id)
    {
        return (new StaffService)->addStaffAvailability($request, $id);
    }

    public function listStaffAvailability(Request $request,$id)
    {
        return (new StaffService)->listStaffAvailability($request,$id);
    }

    public function updateStaffAvailability(Request $request , $staffId,$id)
    {
        return (new StaffService)->updateStaffAvailability($request, $staffId,$id); 
    }

    public function deleteStaffAvailability(Request $request, $staffId,$id)
    {
        return (new StaffService)->deleteStaffAvailability($request, $staffId,$id);  
    }

    public function addStaffRole(Request $request, $id)
    {
        return (new StaffService)->addStaffRole($request, $id);
    }

    public function listStaffRole(Request $request,$id)
    {
        return (new StaffService)->listStaffRole($request,$id);
    }

    public function updateStaffRole(Request $request , $staffId,$id)
    {
        return (new StaffService)->updateStaffRole($request, $staffId,$id); 
    }

    public function deleteStaffRole(Request $request ,$staffId, $id)
    {
        return (new StaffService)->deleteStaffRole($request,$staffId, $id); 
    }

    public function addStaffProvider(Request $request, $id)
    {
        return (new StaffService)->addStaffProvider($request,$id);
    }

    public function listStaffProvider(Request $request,$id)
    {
        return (new StaffService)->listStaffProvider($request,$id);
    }

    public function updateStaffProvider(Request $request , $staffId,$id )
    {
        return (new StaffService)->updateStaffProvider($request , $staffId,$id); 
    }

    public function deleteStaffProvider(Request $request , $staffId,$id)
    {
        return (new StaffService)->deleteStaffProvider($request , $staffId,$id);
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
