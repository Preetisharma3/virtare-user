<?php

namespace App\Transformers\Vital;

use App\Transformers\Patient\PatientGoalTransformer;
use League\Fractal\TransformerAbstract;
use App\Transformers\Patient\PatientVitalTransformer;

class VitalFieldTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'vitalFieldId' => $data->vitalFieldId,
			'goals'=>$data->patientGoal ? fractal()->collection($data->patientGoal)->transformWith(new PatientGoalTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(): [],
		];
	}
}
