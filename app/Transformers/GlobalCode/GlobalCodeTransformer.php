<?php

namespace App\Transformers\GlobalCode;

use League\Fractal\TransformerAbstract;


class GlobalCodeTransformer extends TransformerAbstract
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
    public function transform($data): array
    {
        return [
            'id'=>$data->id,
            'globalCodeCategory'=>$data->globalCodeCategory->name,
			'name'=>$data->name,
            'description'=>$data->description
		];
    }
}
