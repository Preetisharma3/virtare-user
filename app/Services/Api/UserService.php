<?php

namespace App\Services\Api;

use App\Models\User\User;
use App\Transformers\User\UserTransformer;
use Exception;

class UserService
{
    public function userProfile($request)
    {
        try {
            $data = User::where('id', auth()->user()->id)->first();
            return fractal()->item($data)->transformWith(new UserTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
