<?php

namespace App\Transformers\Staff;

use App\Transformers\Provider\ProviderTransformer;
use League\Fractal\TransformerAbstract;


class StaffProviderTransformer extends TransformerAbstract
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
            "provider" => fractal()->item($data->providers)->transformWith(new ProviderTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(),
		];
    }
}
