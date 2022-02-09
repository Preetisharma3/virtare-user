<?php

namespace App\Transformers\GeneralParameter;

use League\Fractal\TransformerAbstract;

class GeneralParameterTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    { 
        return [
            'id' => $data->id,
            'udid'=>$data->udid,
            'generalParameterGroup'=>$data->generalParameterGroup->name,
            'type'=>$data->vitalFieldId,
            'highLimit'=>$data->highLimit,
            'lowLimit'=>$data->lowLimit
        ];
    }
}
