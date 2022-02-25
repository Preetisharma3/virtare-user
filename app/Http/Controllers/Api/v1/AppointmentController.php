<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\AppointmentService;
use App\Http\Requests\Appointment\AppointmentRequest;

class AppointmentController extends Controller
{
  public function newAppointments(Request $request)
  {
    return (new AppointmentService)->newAppointments($request);
  }
  
  public function todayAppointment(Request $request,$id = null)
  {
    return (new AppointmentService)->todayAppointment($request,$id);
  }

  public function addAppointment(request $request,$id = null){
    return (new AppointmentService)->addAppointment($request,$id);
  }

  public function appointmentList(request $request,$id = null){
    return (new AppointmentService)->appointmentList($request,$id);
  }

  public function appointmentSearch(request $request){
    return (new AppointmentService)->appointmentSearch($request);
  }

}
