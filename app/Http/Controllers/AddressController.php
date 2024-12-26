<?php

namespace App\Http\Controllers;

use App\Models\Euser;
use App\Models\State;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function manageAddress(Request $request): View
    {
        // $userdata = Euser::where('id', Auth::guard('euser')->user()->id)->first();
        $state = State::all();

        $addressdata = UserAddress::where('user_id', Auth::guard('euser')->user()->id)->get();

        return view('eusers.address.manage-address', compact('state', 'addressdata'));
    }

    public function storeAddress(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_name' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'landmark' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        // Get the authenticated user
        $user = Auth::guard('euser')->user();

        if ($user) {
            // Create a new address record associated with the user
            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->first_name = $request->first_name;
            $address->middle_name = $request->middle_name;
            $address->last_name = $request->last_name;
            $address->phone = $request->phone;
            $address->country_id = $request->country_id;
            $address->state_id = $request->state_id;
            $address->city_name = $request->city_name;
            $address->zip_code = $request->zip_code;
            $address->landmark = $request->landmark;
            $address->apartment = $request->apartment;
            $address->address = $request->address;
            $address->address_type = $request->address_type;
            $address->save();

            return response()->json(['success' => 'Address saved successfully.']);
        } else {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }
    }

    public function editAddress($id)
    {
        $addressdata = UserAddress::where('user_id', Auth::guard('euser')->user()->id)->get();
        $address = UserAddress::find($id);

        if ($address && $address->user_id === Auth::guard('euser')->user()->id) {
            $state = State::all();
            return view('eusers.address.manage-address', compact('state', 'address', 'addressdata'));
        }

        return redirect()->route('euser.manageaddress')->with('error', 'Address not found.');
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_name' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'landmark' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        $address = UserAddress::find($id);

        if ($address && $address->user_id === Auth::guard('euser')->user()->id) {
            $address->first_name = $request->first_name;
            $address->middle_name = $request->middle_name;
            $address->last_name = $request->last_name;
            $address->phone = $request->phone;
            $address->country_id = $request->country_id;
            $address->state_id = $request->state_id;
            $address->city_name = $request->city_name;
            $address->zip_code = $request->zip_code;
            $address->landmark = $request->landmark;
            $address->apartment = $request->apartment;
            $address->address = $request->address;
            $address->address_type = $request->address_type;
            $address->save();

            return response()->json(['success' => 'Address updated successfully.']);
        }

        return response()->json(['error' => 'Address not found.'], 404);
    }

    public function destroy($id)
    {
        $address = UserAddress::find($id);

        if ($address && $address->user_id === Auth::guard('euser')->user()->id) {
            $address->delete();
            return response()->json(['success' => true, 'message' => 'Address deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Address not found.'], 404);
    }

    public function getAddress($id)
    {
        $userId = Auth::guard('euser')->user()->id;

        // Find address
        $address = UserAddress::where('id', $id)->where('user_id', $userId)->first();

        if ($address) {
            return response()->json($address);
        } else {
            return response()->json(['error' => 'Address not found.'], 404);
        }
    }
}
