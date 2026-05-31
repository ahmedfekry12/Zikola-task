<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return helper::ApiResponse(200 , "created" , [
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($loginType, $request->login)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return helper::ApiResponse(200 , "Logged in" , [
            'user' => $user,
            'token' => $token
        ]);
    }
}
