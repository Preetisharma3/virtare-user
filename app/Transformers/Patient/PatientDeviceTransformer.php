<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientDeviceTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data)
	{ 
		return [
			'otherDevice' => $data->otherDevice->name,
            'status'=>$data->status,
            'patientId'=>$data->patientId
		];
	}


}