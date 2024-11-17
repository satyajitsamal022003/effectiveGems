<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function listtestimonial()
    {
        $testimonials = Testimonial::all();
        return view('admin.testimonial.list', compact('testimonials'));
    }

    public function addtestimonial()
    {
        return view('admin.testimonial.add');
    }

    public function storetestimonial(Request $request)
    {
        $request->validate([
            'authorName' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        try {
            $testimonial = new Testimonial();
            $testimonial->userName = $request->input('authorName');
            $testimonial->designation = $request->input('designation');
            $testimonial->heading = $request->input('heading');
            $testimonial->description = $request->input('testimonialText');
            $testimonial->status = $request->has('status') ? 1 : 0;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('testimonials'), $imageName);
                $testimonial->userImage = 'testimonials/' . $imageName;
            }

            $testimonial->save();

            return redirect()->route('admin.listtestimonial')
                ->with('message', 'Testimonial added successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function edittestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function updatetestimonial(Request $request, $id)
    {
        $request->validate([
            'authorName' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        try {
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->userName = $request->input('authorName');
            $testimonial->designation = $request->input('designation');
            $testimonial->heading = $request->input('heading');
            $testimonial->description = $request->input('testimonialText');
            $testimonial->status = $request->has('status') ? 1 : 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('testimonials'), $imageName);
                $testimonial->userImage = 'testimonials/' . $imageName;
            }

            $testimonial->save();

            return redirect()->route('admin.listtestimonial')
                ->with('message', 'Testimonial updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function toggleOnStatus(Request $request)
    {
        $category = Testimonial::findOrFail($request->testimonialId);
        $category->status = $request->status;
        $category->save();

        return response()->json(['message' => 'Testimonial status updated successfully!', 'success' => true]);
    }

    public function deletetestimonial($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);

            if ($testimonial->userImage && file_exists(public_path($testimonial->userImage))) {
                unlink(public_path($testimonial->userImage));
            }

            $testimonial->delete();

            return redirect()->route('admin.listtestimonial')
                ->with('message', 'Testimonial deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
