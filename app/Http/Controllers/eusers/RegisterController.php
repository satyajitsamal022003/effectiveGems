<?php

namespace App\Http\Controllers\eusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Euser; // Model for the eusers table
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function postsignup(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|unique:eusers,email',
            'password' => 'required|string|min:8',
            // 'mobile' => 'required|digits:10|unique:eusers,mobile',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create a new user
        Euser::create([
            'first_name' => $request->f_name,
            'last_name' => $request->l_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'mobile' => $request->mobile,
        ]);

        // Redirect to a success page or login
        return back()->with('message', 'Account created successfully.');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['mobile' => 'required|digits:10']);

        $mobile = $request->mobile;

        $otp = rand(100000, 999999);

        $apikey = '5xP9YXeSUnRUqqEw';
        $senderid = 'EFGEMS';
        $templateid = '110xxxxxxxxxx592';
        $message_content = urlencode("Dear User, Your OTP For Login is $otp. Regards -Effective Gems");
        $url = "http://text2india.store/vb/apikey.php?apikey=$apikey&senderid=$senderid&number=$mobile&message=$message_content&templateid=$templateid";

        try {
            $response = file_get_contents($url);

            if ($response) {
                session(['otp' => $otp, 'mobile' => $mobile]);

                return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
            }

            return response()->json(['success' => false, 'message' => 'Failed to send OTP.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error occurred: ' . $e->getMessage()]);
        }
    }




    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        if (session('otp') == $request->otp && session('mobile') == $request->mobile) {
            $user = Euser::where('mobile', $request->mobile)->first();

            if ($user) {
                $user->update(['is_mobile_verified' => 1]);
                Auth::guard('euser')->login($user);
                return response()->json(['success' => true, 'message' => 'Logged in successfully.']);
            }

            $randomPassword = Str::random(8);
            $mobile = $request->mobile;

            $newUser = Euser::create([
                'mobile' => $request->mobile,
                'password' => Hash::make($randomPassword), 
                'is_mobile_verified' => 1
            ]);

            $apikey = '5xP9YXeSUnRUqqEw';
            $senderid = 'EFGEMS';
            $templateid = '110xxxxxxxxxx592';
            $message = "Welcome! Your account has been created. Mobile: $mobile, Password: $randomPassword";
            $message_content = urlencode($message);
            $url = "http://text2india.store/vb/apikey.php?apikey=$apikey&senderid=$senderid&number=$mobile&message=$message_content&templateid=$templateid";

            try {
                $response = file_get_contents($url);

                if ($response) {
                    \Log::info("SMS sent successfully to {$request->mobile}: Response = $response");
                } else {
                    \Log::error("SMS sending failed for {$request->mobile}: No response received.");
                }
            } catch (\Exception $e) {
                \Log::error("Error sending SMS to {$request->mobile}: " . $e->getMessage());
            }

            Auth::guard('euser')->login($newUser);
            return response()->json(['success' => true, 'message' => 'Account created and logged in successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP.']);
    }
}
