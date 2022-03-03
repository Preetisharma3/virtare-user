<?php

namespace App\Transformers\CPTCode;

use League\Fractal\TransformerAbstract;


class CPTCodeTransformer extends TransformerAbstract
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
                'udid' =>$data->udid,
                'serviceid'=>$data->service ? $data->service->udid:'',
                'service' => $data->service ? $data->service->name:'',
                'provider' => $data->provider ? $data->provider->name:'',
			    'name'=>$data->name,
                'billingAmout'=>$data->billingAmout,
                'status'=> $data->isActive ? True : False,
                'description' => $data->description,
                'duration' => $data->duration->name,
                'durationId' => $data->duration->udid,
		];
    }
}
