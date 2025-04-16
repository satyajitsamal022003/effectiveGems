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
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
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
            'min_quantity' => $request->min_quantity,
            'is_combo' => $request->is_combo,
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

        if (empty($req->couponName)) {
            return response()->json([], 204); // Stop here with 204 No Content
        }
    
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
        $foundCourierTypeId2 = false;
        $subtotal = $cartItems->sum(function ($item) use (&$totalDelPrice, &$foundCourierTypeId2) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);
            $product = Product::find($item->product_id);
            $deliveryPrice = 0;

            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
    
                if ($courierType->id == 2) {
                    if (!$foundCourierTypeId2) {
                        $foundCourierTypeId2 = true;
                    } else {
                        $deliveryPrice = 0;
                    }
                } elseif ($courierType->id == 3 || ($courierType->id == 4 && $product->categoryId != 1)) {
                    $deliveryPrice *= $item->quantity;
                }
            }

            $totalDelPrice += $deliveryPrice;

            if ($product->categoryId != 1) {
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            } else {
                return ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
            }
        });

        $total = $subtotal - $totalDelPrice;
        $discount = 0;
        $totalApplicableAmount = 0;
        $productSpecificDiscount = 0;

        // Whole site discount
        if ($coupon->wholeSite) {
            if ($coupon->type == 2) {
                $discount = ($total * $coupon->value) / 100;
            } else {
                $discount = min($coupon->value, $total);
            }
        } else {
            $productIds = !empty($coupon->products) ? explode(',', $coupon->products) : [];
            $isApplicable = false;

            if ($coupon->is_combo && !empty($productIds)) {
                $productQuantities = [];
        
                // Count quantities of each product in the combo
                foreach ($cartItems as $item) {
                    if (in_array($item->product_id, $productIds)) {
                        if (!isset($productQuantities[$item->product_id])) {
                            $productQuantities[$item->product_id] = 0;
                        }
                        $productQuantities[$item->product_id] += $item->quantity;
                    }
                }
        
                // Ensure all combo products are present
                $comboCount = PHP_INT_MAX;
                foreach ($productIds as $productId) {
                    $qty = $productQuantities[$productId] ?? 0;
                    $comboCount = min($comboCount, floor($qty / $coupon->min_quantity));
                }
        
                if ($comboCount > 0) {
                    $isApplicable = true;
                    // Calculate total value of one combo set
                    $comboTotal = 0;
                    foreach ($cartItems as $item) {
                        if (in_array($item->product_id, $productIds)) {
                            $comboTotal += $item->productDetails->priceB2C * $coupon->min_quantity;
                        }
                    }
        
                    if ($coupon->type == 2) {
                        $discount = ($comboTotal * $coupon->value / 100) * $comboCount;
                    } else {
                        $discount = $coupon->value * $comboCount;
                    }
                }
        
                if (!$isApplicable) {
                    return response()->json(['message' => 'Combo coupon not applicable'], 422);
                }
            } 
            else {
            // Initialize product/category/subcategory discount calculation
            
            $categoryIds = !empty($coupon->categories) ? explode(',', $coupon->categories) : [];
            $subCategoryIds = !empty($coupon->subCategories) ? explode(',', $coupon->subCategories) : [];


            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                $itemTotal = $item->productDetails->priceB2C * $item->quantity; // Ensure quantity is included
            
                // Check if coupon applies to products
                if (in_array($item->product_id, $productIds)) {
                    if ($item->quantity >= $coupon->min_quantity) {
                        $isApplicable = true;
                        if ($coupon->type == 2) {
                            $productSpecificDiscount += ($itemTotal * $coupon->value) / 100;
                        } else {
                            $productSpecificDiscount += $coupon->value;
                        }
                    }
                }
            
                // Check if coupon applies to categories
                if (in_array($product->categoryId, $categoryIds)) {
                    $isApplicable = true;
                    $totalApplicableAmount += $itemTotal;
                }
            
                // Check if coupon applies to subcategories
                if (in_array($product->subCategoryId, $subCategoryIds)) {
                    $isApplicable = true;
                    $totalApplicableAmount += $itemTotal;
                }
            }


            if (!$isApplicable) {
                return response()->json(['message' => 'Coupon not applicable'], 422);
            }

            // Apply discount on total applicable amount
            if (!empty($productIds)) {
                // For product-specific coupons, use the calculated productSpecificDiscount
                $discount = $productSpecificDiscount;
            } else {
                // For category/subcategory coupons, use the original logic
                if ($coupon->type == 2) {
                    $discount = ($totalApplicableAmount * $coupon->value) / 100;
                } else {
                    $discount = min($coupon->value, $totalApplicableAmount);
                }
            }
         }
        }

        $isCOD = $req->has('isCOD') && $req->isCOD == 1;

        if ($isCOD) {
            session(['isCOD' => true]);
        } else {
            session()->forget('isCOD');
            session()->forget('cod_applied');
        }

        // Calculate final total
        $finalSubtotal = max(0, $total - $discount);
        $finalAmount = $finalSubtotal + $totalDelPrice;

        if ($isCOD) {
            $finalAmount += 30;
            session(['cod_applied' => true]);
        }


        session(['coupon' => [
            'code' => $coupon->code,
            'discount' => $discount,
        ]]);


        return response()->json([
            'total' => $total,
            'discount' => $discount,
            'finalAmount' => $finalAmount,
            'finalSubtotal' => $finalSubtotal,
        ]);
    }
}
