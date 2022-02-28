<?php

namespace App\Transformers\CPTCode;

use League\Fractal\TransformerAbstract;


class ServiceTransformer extends TransformerAbstract
{
   
    protected $defaultIncludes = [
        //
    ];
    
   
    protected $availableIncludes = [
        //
    ];
    
    
    public function transform($data): array
    {
        return [
                'udid' => $data->udid,
                'name' => $data->name,
                'status'=> $data->isActive ? True : False
		];
    }
}
