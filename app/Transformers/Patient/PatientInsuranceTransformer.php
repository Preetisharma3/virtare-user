<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\Inventory\InventoryTransformer;

class PatientInsuranceTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->patientInsuranceUdid,
            'insuranceNumber' => $data->insuranceNumber,
            'expirationDate' => $data->expirationDate,
            'insuranceName' => $data->insuranceName,
            'insuranceType' => $data->insuranceType,
        ];
    }
}
