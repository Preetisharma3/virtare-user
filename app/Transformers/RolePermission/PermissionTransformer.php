<?php

namespace App\Transformers\RolePermission;

use App\Transformers\Screen\ScreenTransformer;
use App\Transformers\Action\ActionTransformer;
use App\Transformers\Module\ModuleTransformer;
use League\Fractal\TransformerAbstract;


class PermissionTransformer extends TransformerAbstract
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
                "name" => $data->name,
                "description"=>$data->description,
                "screens" => fractal()->collection($data->screens)->transformWith(new ScreenTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray()
        ];
    }
}