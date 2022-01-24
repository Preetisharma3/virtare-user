<?php

namespace App\Transformers\Module;

use League\Fractal\TransformerAbstract;


class ModuleTransformer extends TransformerAbstract
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
			    'name'=>$data->name,
                'description'=>$data->description,
		];
    }
}
