<?php

namespace App\Transformers\Vital;

use League\Fractal\TransformerAbstract;

class VitalTypeFieldTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id' => $data->id,
			'field' => $data->VitalField->name,
		];
	}
}
