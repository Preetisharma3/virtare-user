<?php

namespace App\Transformers\Action;

use League\Fractal\TransformerAbstract;


class RoleActionTransformer extends TransformerAbstract
{
   
    protected $defaultIncludes = [
        //
    ];
    
   
    protected $availableIncludes = [
        //
    ];
    
    
    public function transform($data): array
    {
       // dd($data);
        return [
			    'name'=>$data->name,
                'controller'=>$data->controller,
                'function' => $data->function,
		];
    }
}
