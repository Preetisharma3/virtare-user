<?php

namespace App\Transformers\Device;

use League\Fractal\TransformerAbstract;
class DeviceModelTransformer extends TransformerAbstract
{


    protected $showData;

	public function __construct($showData = true)
	{
		$this->showData = $showData;
	}
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
            'deviceType'=>$data->deviceType,
            'modelNumber'=>$data->modelNumber,
        ];
    }
}
