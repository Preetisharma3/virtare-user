<?php

namespace App\Transformers\AccessRoles;

use League\Fractal\TransformerAbstract;


class AssignedRoleActionTransformer extends TransformerAbstract
{
   
    protected $defaultIncludes = [
       
    ];
    
   
    protected $availableIncludes = [
       
    ];
    
    
    public function transform($data): array
    {
        return [$data->actionId];
    }
}
