<?php

namespace App\Transformers\Program;

use League\Fractal\TransformerAbstract;

class ProgramTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->id,
            'description'=>$data->description,
            'type'=>$data->type->name,
		];
	}
}
