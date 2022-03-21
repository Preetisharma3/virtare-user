<?php

namespace App\Services\Api;

use Exception;

class LoginService
{
    public function logout($request)
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'User successfully signed out']);
        } catch (Exception $e) {
            return response()->json(['message' => 'User successfully signed out'],  200);
        }
    }
}
