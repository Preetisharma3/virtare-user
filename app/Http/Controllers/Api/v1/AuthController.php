<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Services\Api\LoginService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Login\LoginTransformer;
use App\Services\Api\PushNotificationService;
use App\Transformers\Login\LoginPatientTransformer;

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
      $deviceToken = $request->deviceToken;
      $deviceType = $request->deviceType;
      if ($request->deviceType == 'ios') {
        $pushNotification = new PushNotificationService();
        $deviceToken = $pushNotification->ios_token($deviceToken);
      }


      User::where('deviceToken', $deviceToken)->update([
        "deviceToken" => "",
        "deviceType" => ""
      ]);
      User::where('id', Auth::id())->update([
        "deviceToken" => $deviceToken,
        "deviceType" => $deviceType,
        "updatedBy" => Auth::id()
      ]);
      $user = User::where('email', $request->email)->with('roles', 'staff', 'patient')->firstOrFail();
      $data = array(
        'token' => $token,
        'user' => $user
      );
      if ($user->roleId == 4) {
        return fractal()->item($data)->transformWith(new LoginPatientTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
      } else {
        return fractal()->item($data)->transformWith(new LoginTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
      }
    } else {
      return response()->json(['message' => trans('messages.unauthenticated')], 401);
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


  protected function createNewToken($token)
  {
    return response()->json([
      'token' => $token,
      'expires_in' => auth()->factory()->getTTL() * 100,
    ]);
  }
}
