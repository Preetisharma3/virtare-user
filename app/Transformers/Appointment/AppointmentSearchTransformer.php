<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;


class AppointmentSearchTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            "id"=>$data->id,
            "date"=>strtotime($data->startDate),
            "notes"=>$data->note,
            "duration"=>$data->duration,
            "appointmentType"=>$data->appointmentType,
            'time'=>strtotime($data->startDate),
            "patient"=>$data->patient,
            "staff"=>$data->staff ,
            "patient_id"=>$data->patient_id,
            "staff_id"=>$data->staff_id ,
            'conferenceId'=>@$data->conferenceId,
        ];
    }
}
