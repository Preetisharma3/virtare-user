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
			'id'=>$data->patientPhysicianUdid,
            'name'=>$data->name,
            'designation'=>$data->designation,
            'phoneNumber'=>$data->phoneNumber,
            'email'=>$data->email,
            'fax'=>$data->fax,
            'sameAsReferal'=>$data->sameAsReferal,
			'profile_photo'=>(!empty($data->profilePhoto))&&(!is_null($data->profilePhoto)) ? URL::to('/').'/'.$data->profilePhoto : "",
		];
	}
}
