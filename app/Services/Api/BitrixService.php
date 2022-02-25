<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use App\Models\Vital\VitalTypeField;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BitrixField\BitrixField;
use App\Transformers\BitrixField\BitrixFieldTransformer;

class BitrixService
{
    public function getBitrixFiled($request)
    {
        $data =  BitrixField::all();
        return fractal()->collection($data)->transformWith(new BitrixFieldTransformer())->toArray();
    }
}
