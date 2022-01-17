<?php

namespace App\Transformers\Widget;

use League\Fractal\TransformerAbstract;

class WidgetUpdateTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data)
    {
        return [
           $data
        ];
    }
}
