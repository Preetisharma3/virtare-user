<?php

namespace App\Transformers\AuditTimeLog;

use League\Fractal\TransformerAbstract;


class AuditTimeLogTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->udid,
            'timeAmount'=>$data->timeAmount,
            'note'=>$data->note,
            'createdBy'=>ucfirst($data->user->staff->firstName).' '.ucfirst($data->user->staff->lastName)
        ];
    }
}
