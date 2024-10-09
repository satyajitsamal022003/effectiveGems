<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Couriertype;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function addcouriertype()
    {
        return view('admin.couriertypes.add');
    }

    public function storecouriertype(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'courier_name' => 'required|string|max:200|unique:couriertypes,courier_name',
            'courier_price' => 'required|integer',
        ]);

        // Create a new CourierType record
        $couriertype = new Couriertype();
        $couriertype->courier_name = $validatedData['courier_name'];
        $couriertype->courier_price = $validatedData['courier_price'];
        $couriertype->save();

        // Redirect back with success message
        return redirect()->route('admin.listcouriertype')->with('message', 'New Courier Type Created!');
    }

    public function listcouriertype()
    {
        // Get all courier types
        $couriertypes = Couriertype::all();
        return view('admin.couriertypes.list', compact('couriertypes'));
    }

    public function couriertypestatus(Request $request)
    {
        // Find the courier type by ID and update status
        $couriertype = Couriertype::findOrFail($request->courierTypeId);
        $couriertype->status = $request->status;
        $couriertype->save();

        return response()->json(['message' => 'Courier Type Status updated successfully!', 'success' => true]);
    }

    public function deletecouriertype($id)
    {
        // Find and delete the courier type by ID
        $couriertype = Couriertype::findOrFail($id);
        $couriertype->delete();

        return redirect()->route('admin.listcouriertype')->with('message', 'Courier Type Deleted successfully!');
    }

    public function editcouriertype($id)
    {
        // Find the courier type to edit
        $couriertype = Couriertype::find($id);
        return view('admin.couriertypes.edit', compact('couriertype'));
    }

    public function updatecouriertype(Request $request, $id)
    {
        // Validate the updated fields
        $validatedData = $request->validate([
            'courier_name' => 'required|string|max:200|unique:couriertypes,courier_name,' . $id,
            'courier_price' => 'required|integer'
        ]);

        // Find the courier type and update the fields
        $couriertype = Couriertype::findOrFail($id);
        $couriertype->courier_name = $validatedData['courier_name'];
        $couriertype->courier_price = $validatedData['courier_price'];
        $couriertype->save();

        return redirect()->route('admin.listcouriertype')->with('message', 'Courier Type Updated Successfully!');
    }
}
