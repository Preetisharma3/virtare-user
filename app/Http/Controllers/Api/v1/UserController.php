<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\UserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

  public function userProfile(Request $request)
  {
    return (new UserService)->userProfile($request);
  }
}
