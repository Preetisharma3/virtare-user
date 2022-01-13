<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;
use App\Transformers\Status\StatusTransformer;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\CareCoordinator\CareCoordinatorTransformer;
use App\Transformers\CareCoordinator\CareCoordinatorAvailabilityTransformer;

class TodayAppointmentTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id'=>$data->id,
            'patient_name'=>$data->patient->full_name,
            'date_time' => $data->date .' '.$data->time->start_time,
            'appointment_with'=>$data->coordinator->first_name.' '.$data->coordinator->last_name,
        ];
    }
}
