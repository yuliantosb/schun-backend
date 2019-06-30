<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use App\ForgotPassword;
use App\Mail\ForgotPassword as ForgotPasswordEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|string|min:6',
        ]);

        $token = Str::random(60);

        if (User::where('email', $request->email)->orWhereHas('employee', function($where) use ($request){
                $where->where('username', $request->email);
            })->exists()) {
            $user = User::where('email', $request->email)
                        ->orWhereHas('employee', function($where) use ($request){
                            $where->where('username', $request->email);
                        })
                        ->first();
            $auth = Hash::check($request->password, $user->password);
            $user->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            if ($user && $auth) {
                return response()->json([
                    'type' => 'success',
                    'token' => $token,
                    'user' => $user->load('employee'),
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

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users',
        ]);


        $forgot = ForgotPassword::firstOrNew(['email' => $request->email]);
        $forgot->email = $request->email;
        $forgot->token = Str::random(16);
        $forgot->timestamp = strtotime(Carbon::now()->addHours(2));
        $forgot->is_obsolete = false;
        $forgot->save();

        $email = new ForgotPasswordEmail();
        $email->data = $forgot;

        Mail::to($request->email)->send($email);
        return response()->json([
            'type' => 'success',
            'message' => 'Email has been sent'
        ]);

    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $forgot = ForgotPassword::where('token', $request->token)
                                    ->where('email', $request->email);
        
        if ($forgot->exists()) {
            
            $forgots = $forgot->first();
            if ($forgots->is_obsolete) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'The token is obsolete, please resend your reset password email',
                    'timestamp' => strtotime(Carbon::now())
                ], 400);
            } else if ($forgots->timestamp < strtotime(Carbon::now())) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'The token is expired, please resend your reset password email'
                ], 400);
            } else {

                $forgots->is_obsolete = true;
                $forgots->save();

                $user = User::where('email', $request->email)->first();
                $user->password = Hash::make($request->new_password);
                $user->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Your password has been changed!',
                ], 200);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Token or email is invalid'
            ], 400);
        }

    }
}
