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
			'id' => $data->id,
			'vitalField' => $data->vitalField?$data->vitalField:@$data->vitalField->name,
			'value' => $data->value,
			'units'=>$data->units,
			'takeTime'=>strtotime($data->takeTime),
			'startTime'=>strtotime($data->startTime),
			'endTime'=>strtotime($data->endTime),
			'addType'=>$data->addType,
			'createdType'=>$data->createdType,
			'comment'=>$data->comment,
			'lastReadingDate'=>$data->createdAt,
			'deviceInfo'=>$data->deviceInfo,
		];
	}
}
