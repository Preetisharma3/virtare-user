<?php

namespace App\Transformers\RolePermission;

use App\Transformers\Screen\ScreenTransformer;
use App\Transformers\Action\ActionTransformer;
use App\Transformers\Module\ModuleTransformer;
use League\Fractal\TransformerAbstract;


class RolePermissionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data)
    {
        return [
                "name" => $data->role->roles,
                "description"=>$data->role->roleDescription,
                'actions'=> fractal()->item($data->action)->transformWith(new ActionTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(),
                
        ];
    }
}