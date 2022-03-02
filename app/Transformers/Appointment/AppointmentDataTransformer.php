<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Appointment\AppointmentTransformer;

class AppointmentDataTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        if(empty($data)){
            return [];
        }
        return [
            "date"=>strtotime(@$data->startDateTime),
            "notes"=>@$data->notes->note,
            'conferenceId'=>@$data->conferenceId,
            'time'=>strtotime(@$data->startDateTime),
            'appointmentType'=>$data->appointmentType->name,
            "patient"=>@$data->patient->firstName." ".@$data->patient->lastName,
            "patientDetailed"=>@$data->patient != null ? fractal()->item($data->patient)->transformWith(new PatientTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : array(),
            "staff"=>@$data->staff != null ? fractal()->item($data->staff)->transformWith(new StaffTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : array()
        ];
    }
}
