<?php

namespace App\Transformers\BitrixField;

use League\Fractal\TransformerAbstract;


class BitrixFieldTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'bitrixId' => $data->bitrixId,
            'patientId' => $data->patientId
        ];
    }
}
