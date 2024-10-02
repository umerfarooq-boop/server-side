<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,player,coach',
        ]);

        $otp = rand(1000, 9999);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'role' => $request->role,
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(1)
        ]);

        Mail::to($user->email)->send(new SendOtpMail($user));
        
        return response()->json([
            'success' => true,
            'message' => 'Otp send on Your Email',
            'User' => $user
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'email' => 'required'
        ]);
        $user = User::where('email',$request->email)->first();

        if ($user && $user->otp == $request->otp && $user->otp_expires_at && Carbon::now()->lt($user->otp_expires_at)) {
            return response()->json([
                'success' => true,
                'message' => 'Email Verified'
            ],200);
            $user->update(['email_verified_at' => true, 'otp' => null, 'otp_expires_at' => null]);
        }else{

            return response()->json(['message' => 'Invalid OTP Time Over'], 400);
        }

    }


    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials',
            ],401);
        }
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token, 
            'user' => auth()->user()
        ]);
        
    }

    public function resendOtp(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $otp = rand(1000,9999);
        $otp_expires_at = Carbon::now()->addMinutes(1);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $otp_expires_at
        ]);
        
        Mail::to($user->email)->send(new SendOtpMail($user));
        return response()->json([
            'success' => true,
            'message' => 'Another OTP send on Your Email',
            'record'  => $user

        ]);
    }

}
