<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;


class AppointmentTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'key'=>$data->id,
            'startDateTime' => $data->startDate.' - '.$data->startTime,
            'startTime' => $data->startTime,
            'startDate' => $data->startDate,
            'patient'=>ucfirst($data->patient->firstName).' '.ucfirst($data->patient->lastName),
            'staff'=>ucfirst($data->staff->firstName).' '.ucfirst($data->staff->lastName),
            'appointmentType'=>$data->appointmentType->name,
            'duration'=>$data->duration->name
        ];
    }
}
