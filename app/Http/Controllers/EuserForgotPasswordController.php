<?php

namespace App\Http\Controllers;

use App\Models\Euser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\EuserResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class EuserForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('eusers.forgot-password');
    }

    /**
     * Handle sending the reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:eusers,email',
        ]);

        // Generate Token
        $token = Str::random(64);

        // Delete any existing token for the email
        DB::table('euserpassword_resets')->where('email', $request->email)->delete();

        // Store token in euserpassword_resets table
        DB::table('euserpassword_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Generate reset link
        $resetLink = route('user.password.reset', ['token' => $token, 'email' => $request->email]);

        // Send email
        Mail::to($request->email)->send(new EuserResetPasswordMail($resetLink));

        return back()->with('message', 'A password reset link has been sent to your email.');
    }

    /**
     * Show reset password form.
     */
    public function showResetPasswordForm($token)
    {
        return view('eusers.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if the email exists in the eusers table
        $user = Euser::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The provided email does not exist in our records.']);
        }

        // Verify token
        $reset = DB::table('euserpassword_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }

        // Update user password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete reset token
        DB::table('euserpassword_resets')->where('email', $request->email)->delete();

        return redirect()->route('eusers.login')->with('message', 'Password reset successfully. You can now log in.');
    }
}
