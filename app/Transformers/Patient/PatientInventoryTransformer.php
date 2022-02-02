<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\Inventory\InventoryTransformer;

class PatientInventoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        $inventory = fractal()->item($data->inventory)->transformWith(new InventoryTransformer())->toArray();


        $field = [
            'id' => $data->id,
            'inventoryId' => $data->inventoryId,
            'isAdded'=>$data->isAdded,
            'status'=>$data->isActive==1?'Active':'Inactive',
            
        ];
        return array_merge($field,$inventory['data']);
    }
}
