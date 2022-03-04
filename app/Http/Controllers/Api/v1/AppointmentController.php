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

  public function todayAppointment(Request $request, $id = null)
  {
    return (new AppointmentService)->todayAppointment($request, $id);
  }

  public function addAppointment(request $request, $id = null)
  {
    return (new AppointmentService)->addAppointment($request, $id);
  }

  public function appointmentList(request $request, $id = null)
  {
    return (new AppointmentService)->appointmentList($request, $id);
  }

  public function appointmentSearch(request $request)
  {
    return (new AppointmentService)->appointmentSearch($request);
  }

  public function conferenceAppointment(request $request)
  {
    return (new AppointmentService)->AppointmentConference($request);
  }

  public function conferenceIdAppointment(request $request,$id)
  {
    return (new AppointmentService)->AppointmentConferenceId($request,$id);
  }

  public function updateAppointment(request $request,$id)
  {
    return (new AppointmentService)->appointmentUpdate($request,$id);
  }

}
