<?php

namespace App\Services\Api;

use Exception;
use App\Models\Flag\Flag;
use App\Transformers\Flag\FlagTransformer;

class FlagService
{
    public function flagList($request)
    {
        try {
            $data = Flag::orderBy('name', 'ASC')->get();
            return fractal()->collection($data)->transformWith(new FlagTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
