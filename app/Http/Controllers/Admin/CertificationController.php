<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function addcertification()
    {
        return view('admin.certifications.add');
    }

    public function storecertification(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|unique:certifications,amount',
        ]);

        $activation = new Certification();
        $activation->amount = $validatedData['amount'];
        $activation->save();

        return redirect()->route('admin.listcertification')->with('message', 'New Certification Created!');
    }

    public function listcertification()
    {
        $activationlist = Certification::all();
        return view('admin.certifications.list', compact('activationlist'));
    }

    public function certificationstatus(Request $request)
    {

        $category = Certification::findOrFail($request->categoryId);
        $category->status = $request->status;
        $category->save();

        return response()->json(['message' => 'Certification Status updated successfully!', 'success' => true]);
    }

    public function deletecertification($id)
    {
        $category = Certification::findOrFail($id);

        $category->delete();

        return redirect()->route('admin.listcertification')->with('message', 'Certification Deleted successfully!');
    }

    public function editcertification($id)
    {
        $activationedit = Certification::find($id);
        return view('admin.certifications.edit', compact('activationedit'));
    }

    public function updatecertification(Request $request, $id)
    {
        $validatedData = $request->validate([
            'amount' => [
                'required',
                'unique:certifications,amount,' . $id,
            ],
        ]);

        $activation = Certification::findOrFail($id);

        $activation->amount = $validatedData['amount'];
        $activation->save();

        return redirect()->route('admin.listcertification')->with('message', 'Certification Updated Successfully!');
    }
}
