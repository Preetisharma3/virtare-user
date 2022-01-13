<?php

namespace App\Services\Api;


use Carbon\Carbon;
use App\Models\Appointment\Appointment;
use App\Transformers\Appointment\AppointmentTransformer;

class AppointmentService
{
    public function futureAppointment($request)
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDate', '>', Carbon::today())->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function newAppointments()
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->latest('createdAt')->take(3)->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function todayAppointment($request)
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDate', Carbon::today())->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }
}
