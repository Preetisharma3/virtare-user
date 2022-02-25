<?php

namespace App\Transformers\Appointment;

use League\Fractal\TransformerAbstract;
use App\Transformers\Appointment\AppointmentTransformer;

class AppointmentListTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'date' => strtotime($data['year']),
            'value' =>  fractal()->collection((object)$data['data'])->transformWith(new AppointmentDataTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(),
        ];
    }
}
