<?php

namespace App\Transformers\Patient;

use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientPhysicianTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->udid,
			'patientId'=>$data->patientId,
            'name'=>$data->name,
            'designation'=>$data->designation->name,
            'designationId'=>$data->designation->id,
            'phoneNumber'=>$data->phoneNumber,
            'fax'=>$data->fax,
            'sameAsReferal'=>$data->sameAsReferal,
            'user' =>$data->user? fractal()->item($data->user)->transformWith(new UserTransformer(false))->toArray():[],
		];
	}
}
