<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Staff\StaffDataTransformer;
use App\Transformers\Appointment\AppointmentTransformer;

class AppointmentDataTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            "date"=>$data->startDate,
            "notes"=>$data->note,
            'time'=>$data->startTime,
            "patient"=>$data->patient->firstName.''.$data->patient->lastName,
            "staff"=>$data->staff != null ? fractal()->item($data->staff)->transformWith(new StaffDataTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : array()
        ];
    }
}
