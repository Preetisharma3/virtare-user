<?php

namespace App\Transformers\Communication;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;


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
          'text'=> $data['data']->type->name,
          'count'=>$data['count']->count,
          'time'=>Carbon::createFromFormat('H', $data['count']->time)->timestamp,
		];
    }
}
