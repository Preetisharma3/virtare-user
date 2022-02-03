<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientReferalTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->referalId,
			'udid'=>$data->udid,
			'patientId'=>$data->patientId,
            'name'=>$data->name,
            'designation'=>$data->designation,
            'phoneNumber'=>$data->phoneNumber,
            'email'=>$data->email,
            'fax'=>$data->fax,
		];
	}
}
