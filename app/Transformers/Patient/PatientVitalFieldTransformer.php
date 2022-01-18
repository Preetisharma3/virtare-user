<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientVitalFieldTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->id,
            'patientId'=>$data->patientId,
			'patientVitalId'=>$data->vital->id,
            'name'=>$data->name,
			'vitalType'=>$data->vital->vitalType->name,
			'value'=>$data->vital->value
		];
	}
}
