<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientStaffTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->udid,
            'patient'=>$data->patient->firstName.' '.$data->patient->middleName.' '.$data->patient->lastName,
            'staffId'=>$data->staff->udid,
            'staff'=>$data->staff->firstName.' '.$data->staff->lastName,
		];
	}
}
