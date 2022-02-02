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

    public function updateStaff(Request $request,$id)
    {
        return (new StaffService)->updateStaff($request, $id); 
    }

    public function addStaffContact(Request $request , $id)
    {
        return (new StaffService)->addStaffContact($request, $id);
    }

    public function listStaffContact(Request $request)
    {
        return (new StaffService)->listStaffContact($request);
    }

    public function updateStaffContact(Request $request , $id)
    {
        return (new StaffService)->updateStaffContact($request, $id);
    }

    public function deleteStaffContact(Request $request, $id)
    {
        return (new StaffService)->deleteStaffContact($request, $id);  
    }

    public function addStaffAvailability(Request $request , $id)
    {
        return (new StaffService)->addStaffAvailability($request, $id);
    }

    public function listStaffAvailability(Request $request)
    {
        return (new StaffService)->listStaffAvailability($request);
    }

    public function updateStaffAvailability(Request $request , $id)
    {
        return (new StaffService)->updateStaffAvailability($request, $id); 
    }

    public function deleteStaffAvailability(Request $request, $id)
    {
        return (new StaffService)->deleteStaffAvailability($request, $id);  
    }

    public function addStaffRole(Request $request, $id)
    {
        return (new StaffService)->addStaffRole($request, $id);
    }

    public function listStaffRole(Request $request)
    {
        return (new StaffService)->listStaffRole($request);
    }

    public function updateStaffRole(Request $request , $id)
    {
        return (new StaffService)->updateStaffRole($request, $id); 
    }

    public function deleteStaffRole(Request $request , $id)
    {
        return (new StaffService)->deleteStaffRole($request, $id); 
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
