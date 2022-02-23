<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;
use App\Transformers\Patient\PatientTransformer;

class StaffPatientTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'patient'=>$data->patientId ? fractal()->item($data->patient)->transformWith(new PatientTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray():'',
        ];
    }
}
