<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientConditionTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->udid,
            'condition'=>$data->condition->name,
            'conditionId'=>$data->condition->id,
            'patientId'=>$data->patientId,
		];
	}
}
