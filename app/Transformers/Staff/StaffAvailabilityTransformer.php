<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;


class StaffAvailabilityTransformer extends TransformerAbstract
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
            'id' => $data->id,
            'udid'=>$data->udid,
            'startTime' => $data->startTime,
            'endTime' => $data->endTime,
            'staffId' => $data->staffId,
            
		];
    }
}
