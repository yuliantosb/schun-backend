<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $token = Str::random(60);

        if (User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->first();
            $auth = Hash::check($request->password, $user->password);
            $user->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            if ($user && $auth) {
                return response()->json([
                    'type' => 'success',
                    'token' => $token
                ], 200);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => trans('auth.failed')
        ], 401);

    }

    public function logout()
    {
        auth()->user()->update(['api_token' => null]);
        return response()->json([
            'type' => 'success',
            'message' => 'Logout success'
        ], 200);
    }
}
