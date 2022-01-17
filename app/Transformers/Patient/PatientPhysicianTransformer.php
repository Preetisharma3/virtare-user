<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientPhysicianTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->id,
			'patientId'=>$data->patientId,
            'name'=>$data->name,
            'designation'=>$data->designation->name,
            'phoneNumber'=>$data->phoneNumber,
            'email'=>$data->user->email,
            'fax'=>$data->fax,
            'sameAsReferal'=>$data->sameAsReferal
		];
	}
}
