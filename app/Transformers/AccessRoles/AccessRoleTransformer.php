<?php

namespace App\Transformers\AccessRoles;

use League\Fractal\TransformerAbstract;


class AccessRoleTransformer extends TransformerAbstract
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
                'id'=>$data->id,
			    'role'=>$data->role,
                'description'=>$data->description
		];
    }
}
