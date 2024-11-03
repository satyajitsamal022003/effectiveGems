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
            'description' => $request->description,
            'value' => $request->value,
            'type' => $request->type,
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
        $coupon->productList = $coupon->products ? explode(',', $coupon->products) : [];
        $coupon->categoriesList = $coupon->categories ? explode(',', $coupon->categories) : [];
        $coupon->subCategoriesList = $coupon->subCategories ? explode(',', $coupon->subCategories) : [];

        $categories = Category::where('status', 1)->get();
        $subCategories = SubCategory::where('status', 1)->get();
        $products = Product::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.coupons.edit', compact('categories', 'coupon', 'products', 'subCategories'));
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

        $coupon->update([
            'wholeSite' => $request->wholeSite ? 1 : 0, // Convert to integer for storage
            'products' => $request->has('productList') ? implode(',', $request->productList) : null, // Convert array to comma-separated string
            'categories' => $request->has('categoriesList') ? implode(',', $request->categoriesList) : null, // Convert array to comma-separated string
            'subCategories' => $request->has('subCategoriesList') ? implode(',', $request->subCategoriesList) : null, // Convert array to comma-separated string
            'startDate' => Carbon::parse($request->startDate),
            'endDate' => Carbon::parse($request->endDate),
            'status' => $request->status,
            'name' => $request->name,
            'description' => $request->description,
            'value' => $request->value,
            'type' => $request->type,
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
            return response()->json(['message' => 'Coupon not found'], 422);
        }

        $coupon->delete();
        return response()->json(['message' => 'Coupon deleted successfully'], 200);
    }
    public function applyCoupon(Request $req)
    {
        // Find the cart using cartId
        $cart = Cart::find($req->cartId);
        $couponName = $req->couponName;

        // Retrieve the coupon by name
        $coupon = Coupon::where("name", $couponName)->first();

        // If the coupon does not exist, return an error response
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 422);
        }

        // Calculate subtotal from the cart items
        $cartItems = $cart ? $cart->items()->with(['productDetails'])->get() : collect();
        $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);
            $product = Product::find($item->product_id);

            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4 && $product->categoryId != 1) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            }
            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;
            if ($product->categoryId != 1) {
                $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            } else {
                $item->totalPrice = ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                return ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
            }
        });
        $total = $subtotal - $totalDelPrice;

        // Initialize discount variable
        $discount = 0;

        // Check if coupon applies to the whole site
        if ($coupon->wholeSite) {
            if ($coupon->type == 2) {
                // Percentage discount
                $discount = ($total * $coupon->value) / 100;
            } else {
                // Flat discount
                $discount = $coupon->value;
            }
        } else {
            // Initialize total applicable amount for products
            $totalApplicableAmount = 0;

            // Check if coupon applies to specific products
            if ($coupon->products) {
                $productIds = explode(',', $coupon->products);
                foreach ($cartItems as $item) {
                    if (in_array($item->product_id, $productIds)) {
                        $totalApplicableAmount += $item->productDetails->priceB2C * $item->quantity;
                    }
                }
            }

            // Check if coupon applies to specific categories
            if ($coupon->categories) {
                $categoryIds = explode(',', $coupon->categories);
                foreach ($cartItems as $item) {
                    $product = Product::find($item->product_id);
                    if (in_array($product->categoryId, $categoryIds)) {
                        $totalApplicableAmount += $item->productDetails->priceB2C * $item->quantity;
                    }
                }
            }

            // Check if coupon applies to specific subcategories
            if ($coupon->subCategories) {
                $subCategoryIds = explode(',', $coupon->subCategories);
                foreach ($cartItems as $item) {
                    $product = Product::find($item->product_id);
                    if (in_array($product->subCategoryId, $subCategoryIds)) {
                        $totalApplicableAmount += $item->productDetails->priceB2C * $item->quantity;
                    }
                }
            }

            // Calculate discount based on applicable total amount
            if ($coupon->type == 2) {
                // Percentage discount on total applicable amount
                $discount = ($totalApplicableAmount * $coupon->value) / 100;
            } else {
                // Flat discount on total applicable amount
                $discount = $coupon->value; // or limit it based on the total applicable amount
            }
        }

        // Calculate final amount after discount
        $finalSubtotal = max(0, $total - $discount); // Ensure final amount does not go below zero
        $finalAmount = max(0, $total - $discount) +$totalDelPrice; // Ensure final amount does not go below zero

        return response()->json([
            'total' => $total,
            'discount' => $discount,
            'finalAmount' => $finalAmount,
            'finalSubtotal' => $finalSubtotal,
        ]);
    }
}
