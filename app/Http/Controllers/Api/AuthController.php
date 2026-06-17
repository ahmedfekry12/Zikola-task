<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\ResetPasswords;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = Auth::guard('api')->login($user);

        if (!$token) {
            return apiResponse(401, "Unauthorized");
        }

        return apiResponse(200, "created", [
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

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return apiResponse(401, "Unauthorized");
        }

        $user = Auth::user();

        return apiResponse(200, "Logged in", [
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return apiResponse(200, "Logged out");
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = (string) rand(100000, 999999);

        $update = $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        if ($update) {
            Mail::to($user->email)
                ->bcc("a7medfekry24@gmail.com")
                ->send(new ResetPasswords($otp));

            return apiResponse(
                200,
                'برجاء فحص هاتفك'
            );
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'new_password' => 'required|string|min:4|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(404, "User not found");
        }

        if ($user->otp !== $request->otp) {
            return apiResponse(400, "Invalid OTP");
        }

        if (now()->isAfter($user->otp_expires_at)) {
            return apiResponse(400, "OTP has expired");
        }

        $user->update([
            'password' => bcrypt($request->{"new_password"}),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return apiResponse(200, "Password reset successfully");
    }

    public function me()
    {
        $user = Auth::user();

        return apiResponse(200, "User retrieved", [
            'user' => $user
        ]);
    }
}
