<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;


class StaffContactTransformer extends TransformerAbstract
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
            'id' => $data->udid,
            'firstName' => $data->firstName,
            'lastName' => $data->lastName,
            'email' => $data->email,
            'phoneNumber' => $data->phoneNumber,
            'staffId' => $data->staffId,
		];
    }
}
