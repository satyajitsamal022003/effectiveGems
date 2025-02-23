<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageCartController extends Controller
{
    public function aCartData()
    {
        $CartItems  = Cart::with(['user', 'productDetails'])->orderBy('id', 'desc')->get();

        return view('admin.cart.frontlist', compact('CartItems'));
    }

    public function CartData($product_id = null)
    {
        $query = Cart::with(['user', 'productDetails'])->orderBy('id', 'desc');

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        // Fetch data
        $Cart = $query->get();

        // Get product name from the first cart entry
        $productName = $Cart->first()->productDetails->productName ?? 'N/A';

        return view('admin.cart.list', compact('Cart', 'productName'));
    }

    public function cartOnStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cartId' => 'required|exists:carts,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart = Cart::findOrFail($request->cartId);
            $cart->follow_off = $request->status;
            $cart->save();

            $statusMessages = [
                0 => 'inactive',
                1 => 'active',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Cart status updated successfully',
                'data' => [
                    'follow_off' => $cart->follow_off,
                    'status_text' => $statusMessages[$cart->follow_off] ?? 'unknown'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Cart status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteCart(Request $request)
    {
        $cartId = $request->cartId;
        Cart::where('id', $cartId)->delete();
        return response()->json(['message' => 'Cart item deleted successfully.']);
    }

    public function massDeleteCart(Request $request)
    {
        $cartIds = $request->cartIds;
        Cart::whereIn('id', $cartIds)->delete();
        return response()->json(['message' => 'Selected cart items deleted successfully.']);
    }
}

