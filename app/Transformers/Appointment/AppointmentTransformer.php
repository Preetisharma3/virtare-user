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
            'startTime' => $data->startTime,
            'startDate' => $data->startDate,
            'patient'=>$data->patient->firstName.' '.$data->patient->lastName,
            'staff'=>$data->staff->firstName.' '.$data->staff->lastName,
            'appointmentType'=>$data->appointmentType->name,
            'duration'=>$data->duration->name
        ];
    }
}
