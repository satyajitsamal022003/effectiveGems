<?php

namespace App\Http\Controllers\eusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Euser; // Model for the eusers table
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        ]);

        // Redirect to a success page or login
        return back()->with('message', 'Account created successfully.');
    }
}
