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
			'id' => $data->udid,
			'vitalField' => @$data->vitalFieldNames->name?$data->vitalFieldNames->name:@$data->vitalField,
			'deviceType' => @$data->deviceType->name?$data->deviceType->name:@$data->deviceType,
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
			'icon'=>(!empty($data->icon))?$data->icon:'',
			'color'=>(!empty($data->flagColor))?$data->flagColor:''
		];
	}
}
