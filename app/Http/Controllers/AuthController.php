<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendOtpMail;
use App\Mail\ForgotOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            $user->update(['email_verified_at' => 1, 'otp' => null, 'otp_expires_at' => 0]);
        }else{

            return response()->json(['message' => 'Invalid OTP Time Over'], 400);
        }

    }

    public function forgotOtp($id){
        $user = User::find($id);
        $otp = rand(1000, 9999);
        $user->forgot_otp = $otp;
        $user->save();
        Mail::to($user->email)->send(new ForgotOtpMail($user));
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Record Get Successfully',
                'user'    => $user
            ],201);
        }
    }

    public function verifyForgotOtp(Request $request, $id) {
        $user = User::find($id);
        // return $user;
        $request->validate([
            'forgot_otp' => 'required|string',
            'email' => 'required|email'
        ]);
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    
        if ($user->forgot_otp === $request->forgot_otp) {
            // Invalidate OTP after successful use
            $user->update(['forgot_otp' => null]);
    
            return response()->json([
                'success' => true,
                'message' => 'Your OTP has been verified successfully.',
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'OTP is invalid or has expired.',
        ], 400);
    }
    
    public function ForgotResendOtp(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $otp = rand(1000,9999);
        $user->forgot_otp = $otp;
        $user->save();
        
        Mail::to($user->email)->send(new ForgotOtpMail($user));
        return response()->json([
            'success' => true,
            'message' => 'Another OTP send on Your Email',
            'record'  => $user

        ]);
    }

    public function resetPassword(Request $request,$id){
        $user = User::find($id);

        $request->validate([
            'password'  => 'required|confirmed'
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Record Found Successfully',
            'user'     => $user
        ],201);
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
    
        // Get the authenticated user
        $user = auth()->user();
    
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
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
