<?php

namespace App\Transformers\Screen;

use League\Fractal\TransformerAbstract;


class ScreenTransformer extends TransformerAbstract
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
                'moduleId'=>$data->moduleId,
		];
    }
}
