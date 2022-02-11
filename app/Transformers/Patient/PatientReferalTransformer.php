<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientReferalTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->patientReferalUdid,
            'name'=>$data->name,
            'designation'=>$data->designation,
            'phoneNumber'=>$data->phoneNumber,
            'email'=>$data->email,
            'fax'=>$data->fax,
		];
	}
}
