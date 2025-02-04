<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
       public function listcat(){
        // Fetch categories with the count of related products, excluding categories with status 2
        $categories = Category::whereNotIn('status', [2])
                              ->withCount('products')  // Count related products
                              ->orderBy('id', 'desc')
                              ->get();
    
        return view('admin.category.listcat', compact('categories'));
    }

    
    public function addcat(){
        return view('admin.category.addcat');
    }

    public function storecat(Request $request){
       // Validation
        $request->validate([
            'categoryName' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'banner' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'sortOrder' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        // Handle File Uploads
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('categories'), $imageName);
            $imagePath = 'categories/' . $imageName; 
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerName = time() . '_' . $banner->getClientOriginalName();
            $banner->move(public_path('categoribanners'), $bannerName);
            $bannerPath = 'categoribanners/' . $bannerName; 
        }

        // Store the data
        Category::create([
            'categoryName' => $request->input('categoryName'),
            'image' => $imagePath,
            'banner' => $bannerPath,
            'sortOrder' => $request->input('sortOrder'),
            'description' => $request->input('description'),
            'status' => $request->has('onoffswitch928') ? 0 : 1,
            'metaTitle' => $request->input('metaTitle'),
            'metaDescription' => $request->input('metaDescription'),
            'seoUrl' => $request->input('seoUrl'),
            'metaKeyword' => $request->input('metaKeyword'),
            'metaImage' => $request->input('metaImage'),
            'imageAlt' => $request->input('imageAlt'),
            'imageTitle' => $request->input('imageTitle'),
            'imageCaption' => $request->input('imageCaption'),
            'imageDesc' => $request->input('imageDesc'),

            
        ]);

      // Redirect with success message
      return redirect()->route('admin.listcat')->with('message', 'Category created successfully!');
    }

    public function editcat($id){

        $category = Category::findOrFail($id);

        return view('admin.category.editcat', compact('category'));

    }

    public function updatecat(Request $request, $id) {
        // Validation
        $request->validate([
            'categoryName' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'banner' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'sortOrder' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);
    
        // Fetch the category
        $category = Category::findOrFail($id);
    
        // Handle File Uploads
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('categories'), $imageName);
            $category->image = 'categories/' . $imageName;
        }
    
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerName = time() . '_' . $banner->getClientOriginalName();
            $banner->move(public_path('categoribanners'), $bannerName);
            $category->banner = 'categoribanners/' . $bannerName;
        }
    
        // Update the other fields
        $category->categoryName = $request->input('categoryName');
        $category->sortOrder = $request->input('sortOrder');
        $category->description = $request->input('description');
        $category->status = $request->has('status') ? 1 : 0;
        // for seo 
        $category->metaTitle = $request->input('metaTitle');
        $category->metaDescription = $request->input('metaDescription');
        $category->seoUrl = $request->input('seoUrl');
        $category->metaKeyword = $request->input('metaKeyword');
        $category->metaImage = $request->input('metaImage');
        // for alternative 
        $category->imageAlt = $request->input('imageAlt');
        $category->imageTitle = $request->input('imageTitle');
        $category->imageCaption = $request->input('imageCaption');
        $category->imageDesc = $request->input('imageDesc');

    
        // Save the updated data
        $category->save();
    
        // Redirect back to the category list with success message
        return redirect()->route('admin.listcat')->with('message', 'Category updated successfully!');
    }
    
    public function deletecat($id)
    {
        // Find the category or fail
        $category = Category::findOrFail($id);
        
        $category->status = 2;
        $category->save(); 
        
        return redirect()->route('admin.listcat')->with('message', 'Category status updated to inactive successfully!');
    }

    public function toggleOnTop(Request $request) {
        $category = Category::findOrFail($request->categoryId);
        $category->onTop = $request->ontop;
        $category->save();

        return response()->json(['message' => 'Category on top updated successfully!', 'success' => true]);
    }

    public function toggleOnFooter(Request $request) {
        $category = Category::findOrFail($request->categoryId);
        $category->onFooter = $request->onfooter;
        $category->save();

        return response()->json(['message' => 'Category on footer updated successfully!', 'success' => true]);
    }

    public function toggleOnStatus(Request $request) {
        $category = Category::findOrFail($request->categoryId);
        $category->status = $request->status;
        $category->save();

        return response()->json(['message' => 'Category status updated successfully!', 'success' => true]);
    }

}
