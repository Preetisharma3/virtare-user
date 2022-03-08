<?php

namespace App\Transformers\GlobalCode;

use League\Fractal\TransformerAbstract;


class GlobalStartEndDateTransformer extends TransformerAbstract
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
            'globalCodeId'=>$data->globalCodeId,
            'conditions'=>$data->conditions,
			'number'=>$data->nm,
            'intervalType'=>$data->intervalType,
            'startDate'=>$data->startDate,
            'endDate'=>$data->endDate
		];
    }
}
