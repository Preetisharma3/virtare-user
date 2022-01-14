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
			'id'=>$data->id,
            'name'=>$data->name,
            'designation'=>$data->globalCode->name,
            'phoneNumber'=>$data->phoneNumber,
            'email'=>$data->email,
            'fax'=>$data->globalCode->name,
		];
	}
}
