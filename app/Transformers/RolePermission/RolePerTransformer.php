<?php

namespace App\Transformers\RolePermission;

use App\Transformers\Screen\ScreenTransformer;
use App\Transformers\Action\ActionTransformer;
use App\Transformers\Module\ModuleTransformer;
use League\Fractal\TransformerAbstract;


class RolePerTransformer extends TransformerAbstract
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
            'roleId'=>$data->roleId,
            'roleName'=>$data->role,
            'moduleId'=>$data->moduleId,
            'moduleName'=>[$data->moduleName],  
            'screenId'=>[$data->screenId],
            'screenName'=>[$data->screenName],
            'actionId'=>$data->actionId?[$data->actionId]:[],
            'actionName'=>$data->actionName?[$data->actionName]:[],
            'actionController'=>$data->actionController?[$data->actionController]:[],
            'actionFunction'=>$data->actionFunction? [$data->actionFunction]:[]
        ];
    }
}
