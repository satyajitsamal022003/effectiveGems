<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activations;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function addactivation()
    {
        return view('admin.activation.add');
    }

    public function storeactivation(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|unique:activations,amount',
        ]);

        $activation = new Activations();
        $activation->amount = $validatedData['amount'];
        $activation->save();

        return redirect()->route('admin.listactivation')->with('message', 'New Activation Created!');
    }

    public function listactivation()
    {
        $activationlist = Activations::all();
        return view('admin.activation.list', compact('activationlist'));
    }

    public function activationstatus(Request $request)
    {

        $category = Activations::findOrFail($request->categoryId);
        $category->status = $request->status;
        $category->save();

        return response()->json(['message' => 'Activation Status updated successfully!', 'success' => true]);
    }

    public function deleteactivation($id)
    {
        $category = Activations::findOrFail($id);

        $category->delete();

        return redirect()->route('admin.listactivation')->with('message', 'Activation Deleted successfully!');
    }

    public function editactivation($id)
    {
        $activationedit = Activations::find($id);
        return view('admin.activation.edit', compact('activationedit'));
    }

    public function updateactivation(Request $request, $id)
    {
        $validatedData = $request->validate([
            'amount' => [
                'required',
                'unique:activations,amount,' . $id,
            ],
        ]);

        $activation = Activations::findOrFail($id);

        $activation->amount = $validatedData['amount'];
        $activation->save();

        return redirect()->route('admin.listactivation')->with('message', 'Activation Updated Successfully!');
    }
}
