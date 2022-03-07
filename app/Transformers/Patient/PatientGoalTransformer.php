<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientGoalTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id'=>$data->udid,
            'lowValue'=>$data->lowValue,
            'highValue'=>$data->highValue,
            'patientId'=>$data->patientId,
            'patientName'=>$data->patient->firstName.' '.$data->patient->lastName,
            'vitalFieldId'=>$data->vitalFieldId,
            'vitalField'=>$data->vitalField->name
        ];
    }
}
