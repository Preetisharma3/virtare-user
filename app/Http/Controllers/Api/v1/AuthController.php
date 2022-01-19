<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Services\Api\LoginService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Login\LoginTransformer;

class AuthController extends Controller
{

  /**
   * @var \Tymon\JWTAuth\JWTAuth
   */
  protected $jwt;

  public function __construct(JWTAuth $jwt)
  {
    $this->jwt = $jwt;
  }

  public function login(request $request)
  {
    if ($token = $this->jwt->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
      $user = User::where('email', $request->email)->with('roles','staff')->firstOrFail();
      if ($user['roles']->roles == $request->role) {
        User::where('id', Auth::id())->update([
          "updatedBy" => Auth::id()
        ]);
        $data = array(
          'token' => $token,
          'user' => $user
        );
        return fractal()->item($data)->transformWith(new LoginTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
      } else {
        return response()->json(['message' => trans('messages.unauthenticated')], 401);
      }
    } else {
      return response()->json(['message' => trans('messages.login_fail')], 401);
    }
  }

  public function logout(Request $request)
  {
    return (new LoginService)->logout($request);
  }

  public function refreshToken()
  {
    return $this->createNewToken(auth()->refresh());
  }


  protected function createNewToken($token){
    return response()->json([
        'token' => $token,
        'expires_in' => auth()->factory()->getTTL() *100,
    ]);
}
}
