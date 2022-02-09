<?php

namespace App\Transformers\AccessRoles;

use League\Fractal\TransformerAbstract;


class AssignedRolesTransformer extends TransformerAbstract
{
   
    protected $defaultIncludes = [
       
    ];
    
   
    protected $availableIncludes = [
       
    ];
    
    
    public function transform($data): array
    {
        return [
               $data
		];
    }
}
