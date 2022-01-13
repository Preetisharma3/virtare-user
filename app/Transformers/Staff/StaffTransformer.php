<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class StaffTransformer extends TransformerAbstract
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
			'staff'=>$data->firstName.' '.$data->lastName,
		];
    }
}
