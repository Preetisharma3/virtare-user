<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\AppointmentService;
use App\Http\Requests\Appointment\AppointmentRequest;

class AppointmentController extends Controller
{
  public function futureAppointment(Request $request)
  {
    return (new AppointmentService)->futureAppointment($request);
  }

  public function newAppointments(Request $request)
  {
    return (new AppointmentService)->newAppointments($request);
  }
  
  public function todayAppointment(Request $request)
  {
    return (new AppointmentService)->todayAppointment($request);
  }

  public function addAppointment(request $request){
    return (new AppointmentService)->addAppointment($request);
  }

  public function appointmentList(request $request){
    return (new AppointmentService)->appointmentList($request);
  }

}
