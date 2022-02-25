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
			'id'=>$data->id,
			'otherDevice' => $data->otherDevice->name,
			'otherDeviceId' => $data->otherDevice->id,
            'status'=>$data->status,
            'patientId'=>$data->patientId
		];
	}


}