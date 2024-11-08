<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    // Display redirects in DataTable format
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $redirects = Redirect::select('id', 'old_url', 'new_url', 'status')->get();

            // Prepare data for DataTables
            $data = [];
            foreach ($redirects as $redirect) {
                $data[] = [
                    'id' => $redirect->id,
                    'old_url' => $redirect->old_url,
                    'new_url' => $redirect->new_url,
                    'status' => $redirect->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>',
                    'action' => '
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $redirect->id . '" data-old-url="' . $redirect->old_url . '" data-new-url="' . $redirect->new_url . '" data-status="' . $redirect->status . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $redirect->id . '">Delete</button>'
                ];
            }

            return response()->json(['data' => $data]);
        }
        $redirects = Redirect::select('id', 'old_url', 'new_url', 'status')->get();

        return view('admin.redirects.index',compact('redirects'));
    }

    // Show the form to create a new redirect
    public function create()
    {
        return view('admin.redirects.create');
    }

    // Store a new redirect
    public function store(Request $request)
    {
        $request->validate([
            'old_url' => 'required|unique:redirects,old_url',
            'new_url' => 'required',
            'status' => 'required',
        ]);

        Redirect::create($request->only('old_url', 'new_url', 'status'));
        return redirect()->route('redirects.index');
    }

    // Show the form to edit an existing redirect
    public function edit($id)
    {
        $redirect = Redirect::findOrFail($id);
        return view('admin.redirects.edit', compact('redirect'));
    }

    // Update an existing redirect
    public function update(Request $request, $id)
    {
        $request->validate([
            'old_url' => 'required|unique:redirects,old_url,' . $id,
            'new_url' => 'required',
            'status' => 'required',
        ]);

        $redirect = Redirect::findOrFail($id);
        $redirect->update($request->only('old_url', 'new_url', 'status'));

        return redirect()->route('redirects.index');
    }

    // Delete a redirect
    public function destroy(Request $request,$id)
    {
        $redirect = Redirect::find($id);
        if ($redirect) {
            $redirect->delete();
            return redirect()->route('redirects.index');
        }
        return response()->json(['message' => 'Redirect not found'], 422);
    }

    // Update the status of a redirect
    public function updateStatus(Request $request)
    {
        $redirect = Redirect::find($request->redirectId);
        if ($redirect) {
            $redirect->status = $request->status;
            $redirect->save();
            return response()->json(['message' => 'Redirect status updated successfully']);
        }
        return response()->json(['message' => 'Redirect not found'], 422);
    }
}
