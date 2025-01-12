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
              'id'=>$data->id,
              'staffId'=>$data->staffId,
              'staffName'=>$data->StaffName,
              'roleId'=>$data->accessRoleId,
              'role'=>$data->role
		];
    }
}
