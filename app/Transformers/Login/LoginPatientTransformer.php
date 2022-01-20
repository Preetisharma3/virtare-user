<?php

namespace App\Transformers\Login;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;
use App\Transformers\User\UserPatientTransformer;

class LoginPatientTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data)
	{
		return [
			'token' => $data['token'],
			'user' => $data['user'] ? fractal()->item($data['user'])->transformWith(new UserPatientTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : array(),
		];
	}
}
