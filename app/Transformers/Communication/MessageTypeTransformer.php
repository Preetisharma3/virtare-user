<?php

namespace App\Transformers\Communication;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Communication\MessageCountTransformer;
use App\Transformers\Communication\MessageTypeCountTransformer;


class MessageTypeTransformer extends TransformerAbstract
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
           // 'text' => $data,
            'count' => $data->count,
            'time' => Carbon::parse('H', $data->time)->timestamp,
		];
    }
}
