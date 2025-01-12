<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientMedicalTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->udid,
            'patientId' => $data->patientId,
            'history' => $data->history
        ];
    }
}
