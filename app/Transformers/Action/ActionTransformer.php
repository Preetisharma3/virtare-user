<?php

namespace App\Transformers\Action;

use League\Fractal\TransformerAbstract;


class ActionTransformer extends TransformerAbstract
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
                'id' => $data->id,
			    'name'=>$data->name,
                'controller'=>$data->controller,
                'function' => $data->function,
		];
    }
}
