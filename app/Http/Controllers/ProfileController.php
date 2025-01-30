<?php

namespace App\Http\Controllers;

use App\Models\Euser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function myProfile(Request $request): View
    {
        $userdata = Euser::where('id', Auth::guard('euser')->user()->id)->first();


        return view('eusers.profile.my-profile', compact('userdata'));
    }

    public function Setting(Request $request): View
    {
        $userdata = Euser::where('id', Auth::guard('euser')->user()->id)->first();

        return view('eusers.profile.setting', compact('userdata'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'nullable|digits:10',
            'gender' => 'required|in:1,2',
            // 'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::guard('euser')->user();

        if ($request->hasFile('profile_img')) {
            $fileName = time() . '.' . $request->profile_img->extension();
            $request->profile_img->move(public_path('/user/assets/images/profile'), $fileName);
            $user->profile_img = $fileName;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->gender = $request->gender;

        $user->save();

        return response()->json(['success' => 'Profile updated successfully']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            // 'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::guard('euser')->user();

        // if (!Hash::check($request->old_password, $user->password)) {
        //     return response()->json(['error' => 'Old password is incorrect'], 400);
        // }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => 'Password changed successfully']);
    }
}
