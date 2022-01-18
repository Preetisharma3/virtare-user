<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\Patient\PatientVitalFieldTransformer;

class PatientVitalTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->id,
            'vitalType'=>$data->vitalType->name,
            'value'=>$data->value,
            'type'=>fractal()->item($data->type)->transformWith(new PatientVitalFieldTransformer())->toArray()
		];
	}
}
