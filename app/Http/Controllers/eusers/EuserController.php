<?php

namespace App\Http\Controllers\eusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EuserController extends Controller
{
    public function postsignin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('euser')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('euser.dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['Invalid credentials'])->withInput();
    }

    public function logout()
    {
        Auth::guard('euser')->logout();
        return redirect()->route('eusers.login')->with('message', 'Logged out successfully!');
    }

    public function dashboard()
    {
        return view('eusers.dashboard');
    }

    public function myorderlist(){
        return view('eusers.myorders.list');
    }

    public function ordersview(){
        return view('eusers.myorders.view');
    }

    public function wishlist(){
        return view('eusers.wishlist');
    }
}
