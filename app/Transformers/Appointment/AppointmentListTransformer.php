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
            'date' => $data['date'],
            'value' =>  fractal()->collection((object)$data['value'])->transformWith(new AppointmentTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(),
        ];
    }
}
