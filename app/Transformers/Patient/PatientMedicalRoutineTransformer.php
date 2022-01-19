<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientMedicalRoutineTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->id,
            'patientId' => $data->patientId,
            'medicine' => $data->medicine,
            'frequency' => $data->frequency,
            'startDate' => $data->startDate,
            'endDate' => $data->endDate,
        ];
    }
}