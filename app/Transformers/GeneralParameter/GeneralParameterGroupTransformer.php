<?php

namespace App\Transformers\GeneralParameter;

use League\Fractal\TransformerAbstract;

class GeneralParameterGroupTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    { 
        return [
            'id' => $data->id,
            'udid'=>$data->udid,
            'name'=>$data->name,
            'generalparameter'=> fractal()->collection($data->generalParameter)->transformWith(new GeneralParameterTransformer())->toArray()
        ];
    }
}
