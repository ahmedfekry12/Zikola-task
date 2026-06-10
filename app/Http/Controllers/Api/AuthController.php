<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = Auth::guard('api')->login($user);

        if (!$token) {
            return helper::ApiResponse(401 , "Unauthorized");
        }

        return helper::ApiResponse(200 , "created" , [
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(Request $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // $user = User::where($loginType, $request->login)->first();
        // $token = $user->createToken('auth_token')->plainTextToken;

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return helper::ApiResponse(401 , "Unauthorized");
        }

        $user = Auth::user();

        return helper::ApiResponse(200 , "Logged in" , [
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        $user = Auth::user();

        return helper::ApiResponse(200 , "User retrieved" , [
            'user' => $user
        ]);
    }
}
