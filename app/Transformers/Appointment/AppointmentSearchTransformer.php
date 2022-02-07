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
            "date"=>strtotime($data->startDate." ".$data->startTime),
            "notes"=>$data->note,
            'time'=>strtotime($data->startDate." ".$data->startTime),
            "patient"=>$data->patient,
            "staff"=>$data->staff 
        ];
    }
}
