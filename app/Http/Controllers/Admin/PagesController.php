<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sortOrder')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $pages)
    {
        return view('admin.pages.edit', compact('pages'));
    }

    public function update(Request $request, Page $pages)
    {
        $validator = Validator::make($request->all(), [
            'pageName' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sortOrder' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'seoUrl' => 'nullable|string|max:255',
            'metaTitle' => 'nullable|string|max:255',
            'metaDescription' => 'nullable|string',
            'metaKeyword' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update page data
        $pages->fill([
            'pageName' => $request->pageName,
            'heading' => $request->heading,
            'description' => $request->description,
            'sortOrder' => $request->sortOrder,
            'status' => $request->has('status') ? 1 : 0, // Checkbox handling
            'seoUrl' => $request->seoUrl,
            'metaTitle' => $request->metaTitle,
            'metaDescription' => $request->metaDescription,
            'metaKeyword' => $request->metaKeyword,
        ]);

        $pages->save();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully');
    }


    // public function updateStatus(Request $request, Page $banner)
    // {
    //     $banner->status = !$banner->status;
    //     $banner->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Banner status updated successfully',
    //         'status' => $banner->status
    //     ]);
    // }

    public function updateStatus(Request $request)
    {
        $page = Page::findOrFail($request->pageId);
        $page->status = $request->status;
        $page->save();

        return response()->json(['message' => 'Page Status updated successfully!', 'success' => true]);
    }

    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:banners,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order data'
            ], 422);
        }

        foreach ($request->orders as $index => $id) {
            Page::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Banner order updated successfully'
        ]);
    }
}
