<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function loginPage()
    {
        return view('pages.login-page');
    }
    public function verifyPage()
    {
        return view('pages.Verify-page');
    }
    public function login(Request $request)
    {
        try {
            $email = $request->email;
            $otp = rand(1000, 9999);

            Mail::to($email)->send(new OTPMail($otp));

            User::updateOrCreate(['email' => $email], ['otp' => $otp]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyLogin(Request $request)
    {
        try {
            $email = $request->email;
            $otp = $request->otp;
            $userInfo = User::where('email', $email)->where('otp', $otp)->first();
            if ($userInfo) {
                User::where('email', $email)->update(['otp' => '0']);
                $token = JWTToken::generateToken($email, $userInfo->id);
                return response()->json([
                    'success' => true,
                    'message' => 'OTP verified successfully',
                    'token' => $token
                ], 200)->cookie('token', $token, time() + 24 * 60 * 60);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {

        try {
            return redirect('/')->cookie('token', '', -1);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
