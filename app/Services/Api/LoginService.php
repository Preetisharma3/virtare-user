<?php

namespace App\Services\Api;

use App\Models\User;
use Exception;
use App\Transformers\Login\LoginTransformer;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class LoginService
{
    public function logout($request)
    {
        try{
            auth()->logout();
            return response()->json(['message' => 'User successfully signed out']);
        } catch (Exception $e) {
            return response()->json(['message' => 'User successfully signed out'],  200);
        }
    }
}