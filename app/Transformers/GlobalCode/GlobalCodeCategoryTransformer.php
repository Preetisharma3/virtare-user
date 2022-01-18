<?php

namespace App\Transformers\GlobalCode;

use League\Fractal\TransformerAbstract;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class GlobalCodeCategoryTransformer extends TransformerAbstract
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
			'name'=>$data->name,
            'globalCode'=> fractal()->collection($data->globalCode)->transformWith(new GlobalCodeTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray()
		];
    }
}
