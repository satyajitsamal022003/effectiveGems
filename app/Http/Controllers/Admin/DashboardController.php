<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }
    public function aprofile(){
        $adminData = Auth::guard('web')->user();
        return view('admin.profile',compact('adminData'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $admin = Auth::guard('web')->user();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return response()->json(['message' => 'Profile updated successfully.']);
    }

    public function showChangePasswordForm()
    {
        return view('admin.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json(['errors' => ['current_password' => ['Current password does not match']]], 422);
        }

        $admin = Auth::guard('web')->user();
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }
}
