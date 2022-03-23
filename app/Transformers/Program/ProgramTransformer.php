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
			'udid' =>$data->udid,
			'id'=>$data->id,
			'name'=>$data->name,
            'description'=>$data->description,
            'type'=>$data->type->name,
			'typeId' => $data->typeId,
			'status' =>$data->isActive,
		];
	}
}
