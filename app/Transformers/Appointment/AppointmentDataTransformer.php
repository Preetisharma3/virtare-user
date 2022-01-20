<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Appointment\AppointmentTransformer;

class AppointmentDataTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        // dd($data->staff);
        return [
            "date"=>$data->startDate,
            "notes"=>$data->note,
            'time'=>$data->startTime,
            "staff"=>$data->staff != null ? fractal()->item($data->staff)->transformWith(new StaffTransformer)->toArray() : array()
        ];
    }
}
