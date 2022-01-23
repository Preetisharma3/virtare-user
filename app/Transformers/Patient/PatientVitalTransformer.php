<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientVitalTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->id,
             'value'=>$data->value,
		];
	}
}
