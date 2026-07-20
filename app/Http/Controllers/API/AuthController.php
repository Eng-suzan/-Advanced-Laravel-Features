<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
  public function register(Request $request)
{
    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|min:8'
    ]);

    $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
    ]);

    $token = $user->createToken('api-token')->plainTextToken;
  return ApiResponse::success([
        'user'=>new UserResource($user),
        'token'=>$token
    ],'Register Successfully');

}

 public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

       $user = User::query()->where('email', $request->input('email')) ->first();
        if (!$user) {
            return ApiResponse::error("User Not Found", code: 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return ApiResponse::error("credentials are incorrect", code: 404);
        }

        $token = $user->createToken("api-token")->plainTextToken;
        $data['user'] = $user;
        $data['token'] = $token;
        return ApiResponse::success($data);
    }
public function logout(Request $request)
{
    $token = $request->user()->currentAccessToken();

    if ($token) {
        $token->delete();
    }

    return ApiResponse::success(message: "Logged Out");
}
}