<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json(['coupons' => $coupons], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Typically, this would return a view for creating a new coupon
        $categories = Category::where('status', 1)->get();
        $subCategories = SubCategory::where('status', 1)->get();
        $products = Product::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.coupons.create',compact('categories','products','subCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Dump and die to see the request data (can remove this later)
        // dd($request->all());
    
        // Validate the request data
        $request->validate([
            'wholeSite' => 'nullable|string',
            'products' => 'nullable|string',
            'categories' => 'nullable|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'status' => 'required|integer',
            'productList' => 'nullable|array', // Validate productList as an array
            'categoriesList' => 'nullable|array', // Validate categoriesList as an array
            'subCategoriesList' => 'nullable|array', // Validate subCategoriesList as an array
        ]);
    
        // Create a new coupon entry in the database
        Coupon::create([
            'wholeSite' => $request->wholeSite ? 1 : 0, // Convert to integer for storage
            'products' => $request->has('productList') ? implode(',', $request->productList) : null, // Convert array to comma-separated string
            'categories' => $request->has('categoriesList') ? implode(',', $request->categoriesList) : null, // Convert array to comma-separated string
            'subCategories' => $request->has('subCategoriesList') ? implode(',', $request->subCategoriesList) : null, // Convert array to comma-separated string
            'startDate' => Carbon::parse($request->startDate),
            'endDate' => Carbon::parse($request->endDate),
            'status' => $request->status,
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return response()->json(['message' => 'Coupon created successfully'], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        return response()->json(['coupon' => $coupon], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        // Typically, this would return a view for editing the coupon
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        $request->validate([
            'wholeSite' => 'nullable|integer',
            'products' => 'nullable|string',
            'categories' => 'nullable|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'status' => 'required|integer'
        ]);

        $coupon->update([
            'wholeSite' => $request->wholeSite,
            'products' => $request->products,
            'categories' => $request->categories,
            'startDate' => Carbon::parse($request->startDate),
            'endDate' => Carbon::parse($request->endDate),
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Coupon updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        $coupon->delete();
        return response()->json(['message' => 'Coupon deleted successfully'], 200);
    }
}
