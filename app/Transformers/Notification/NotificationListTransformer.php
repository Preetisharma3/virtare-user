<?php

namespace App\Transformers\Notification;

use League\Fractal\TransformerAbstract;

class NotificationListTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data)
	{
		return [
			  'date' => $data['year'],
            'value' => $data['data'] ?  fractal()->collection((object)$data['data'])->transformWith(new NotificationTransformer(true))->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(): array(),
		];
	}
}
