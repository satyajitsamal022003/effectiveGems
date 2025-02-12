<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Couriertype;
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
        return view("admin.coupons.list", compact('coupons'));
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
        return view('admin.coupons.create', compact('categories', 'products', 'subCategories'));
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
            'code' => $request->code,
            'description' => $request->description,
            'value' => $request->value,
            'type' => $request->type,
        ]);

        return redirect()->route('coupons.index')->with('message', 'Coupon created successfully!');
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

        // Debug output
        // dd($startDate, $currentDate); 

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 422);
        }

        $currentDate = Carbon::now('Asia/Kolkata'); // Get current time in 'Asia/Kolkata' timezone
        $startDate = Carbon::parse($coupon->startDate)->timezone('Asia/Kolkata'); // Parse start date to 'Asia/Kolkata' timezone

        // Debug output
        // dd($startDate->toDateTimeString(), $currentDate->toDateTimeString()); // Check both dates

        // Compare Carbon instances directly (including both date and time)
        $isDisabled = $currentDate->greaterThan($startDate) ? 'disabled' : ''; // Use full Carbon comparison

        // Debug output for the result


        $coupon->productList = $coupon->products ? explode(',', $coupon->products) : [];
        $coupon->categoriesList = $coupon->categories ? explode(',', $coupon->categories) : [];
        $coupon->subCategoriesList = $coupon->subCategories ? explode(',', $coupon->subCategories) : [];

        $categories = Category::where('status', 1)->get();
        $subCategories = SubCategory::where('status', 1)->get();
        $products = Product::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.coupons.edit', compact('categories', 'coupon', 'products', 'subCategories', 'currentDate', 'isDisabled'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // dd($request->all()); // For debugging, you can remove this after confirming the data

        // Find the coupon by ID
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        // Validate the incoming request
        // $request->validate([
        //     'wholeSite' => 'nullable|string',
        //     'products' => 'nullable|string',
        //     'categories' => 'nullable|string',
        //     'startDate' => 'required|date',
        //     'endDate' => 'required|date|after_or_equal:startDate',
        //     'status' => 'required|integer',
        //     'productList' => 'nullable|array',
        //     'categoriesList' => 'nullable|array',
        //     'subCategoriesList' => 'nullable|array',
        // ]);

        // Update the coupon only with provided fields
        $coupon->update([
            'wholeSite' => $request->has('wholeSite') ? 1 : $coupon->wholeSite, // Only update if provided, otherwise retain old value
            'products' => $request->has('productList') ? implode(',', $request->productList) : $coupon->products,
            'categories' => $request->has('categoriesList') ? implode(',', $request->categoriesList) : $coupon->categories,
            'subCategories' => $request->has('subCategoriesList') ? implode(',', $request->subCategoriesList) : $coupon->subCategories,
            'startDate' => $request->has('startDate') ? Carbon::parse($request->startDate) : $coupon->startDate,
            'endDate' => $request->has('endDate') ? Carbon::parse($request->endDate) : $coupon->endDate,
            'status' => $request->has('status') ? $request->status : $coupon->status,
            'name' => $request->has('name') ? $request->name : $coupon->name,
            'code' => $request->has('code') ? $request->code : $coupon->code,
            'description' => $request->has('description') ? $request->description : $coupon->description,
            'value' => $request->has('value') ? $request->value : $coupon->value,
            'type' => $request->has('type') ? $request->type : $coupon->type,
        ]);

        // Return a success message
        return redirect()->route('coupons.index')->with('message', 'Coupon updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 422);
        }

        $coupon->delete();
        return redirect()->route('coupons.index')->with('message', 'Coupon Deleted!');
    }
    public function updateStatus(Request $request)
    {
        $redirect = Coupon::find($request->couponId);
        if ($redirect) {
            $redirect->status = $request->status;
            $redirect->save();
            return response()->json(['message' => 'Coupon status updated successfully']);
        }
        return response()->json(['message' => 'Coupon not found'], 422);
    }
    public function applyCoupon(Request $req)
    {
        // Find the cart using cartId
        $cart = Cart::find($req->cartId);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $couponName = $req->couponName;

        // Retrieve the coupon by name
        $coupon = Coupon::where("code", $couponName)->first();
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 422);
        }

        $currentDate = Carbon::now('Asia/Kolkata');

        $startDate = Carbon::parse($coupon->startDate)->timezone('Asia/Kolkata');
        $endDate = Carbon::parse($coupon->endDate)->timezone('Asia/Kolkata');

        if ($currentDate->lt($startDate) || $currentDate->gt($endDate)) {
            return response()->json(['message' => 'Coupon expired or not yet valid'], 422);
        }

        // Fetch cart items with product details
        $cartItems = $cart->items()->with('productDetails')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $totalDelPrice = 0;
        $subtotal = $cartItems->sum(function ($item) use (&$totalDelPrice) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);
            $product = Product::find($item->product_id);

            $deliveryPrice = $courierType ? $courierType->courier_price : 0;
            if ($courierType && ($courierType->id == 3 || $courierType->id == 4) && $product->categoryId != 1) {
                $deliveryPrice *= $item->quantity;
            }

            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;

            return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
        });

        $total = $subtotal - $totalDelPrice;
        $discount = 0;
        $totalApplicableAmount = 0;

        // Whole site discount
        if ($coupon->wholeSite) {
            if ($coupon->type == 2) {
                $discount = ($total * $coupon->value) / 100;
            } else {
                $discount = min($coupon->value, $total);
            }
        } else {
            // Initialize product/category/subcategory discount calculation
            $productIds = !empty($coupon->products) ? explode(',', $coupon->products) : [];
            $categoryIds = !empty($coupon->categories) ? explode(',', $coupon->categories) : [];
            $subCategoryIds = !empty($coupon->subCategories) ? explode(',', $coupon->subCategories) : [];

            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                $itemTotal = $item->productDetails->priceB2C * $item->quantity; // Ensure quantity is included
            
                // Check if coupon applies to products
                if (in_array($item->product_id, $productIds)) {
                    $totalApplicableAmount += $itemTotal;
                }
            
                // Check if coupon applies to categories
                if (in_array($product->categoryId, $categoryIds)) {
                    $totalApplicableAmount += $itemTotal;
                }
            
                // Check if coupon applies to subcategories
                if (in_array($product->subCategoryId, $subCategoryIds)) {
                    $totalApplicableAmount += $itemTotal;
                }
            }

            // Apply discount on total applicable amount
            if ($coupon->type == 2) {
                $discount = ($totalApplicableAmount * $coupon->value) / 100;
            } else {
                $discount = min($coupon->value * $cartItems->sum('quantity'), $totalApplicableAmount);
            }
        }

        // Calculate final total
        $finalSubtotal = max(0, $total - $discount);
        $finalAmount = $finalSubtotal + $totalDelPrice;

        return response()->json([
            'total' => $total,
            'discount' => $discount,
            'finalAmount' => $finalAmount,
            'finalSubtotal' => $finalSubtotal,
        ]);
    }
}
