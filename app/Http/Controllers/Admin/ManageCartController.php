<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Validator;

class ManageCartController extends Controller
{
    public function aCartData()
    {
        $CartItems  = CartItem::with(['productDetails', 'cart.userDetails'])->orderBy('id', 'desc')->get();

        return view('admin.cart.frontlist', compact('CartItems'));
    }

    public function getCartData()
    {
        $CartItems = CartItem::with(['productDetails', 'cart.userDetails'])
            ->whereHas('cart') // Ensure only items linked to a valid cart
            ->get();
    
        // If no cart items exist, return an empty response
        if ($CartItems->isEmpty()) {
            return response()->json(['data' => []]);
        }
    
        // Group by product_id and filter out empty products
        $groupedItems = $CartItems->groupBy('product_id')->map(function ($items, $productId) {
            return [
                'product_id' => $productId,
                'product_name' => optional($items->first()->productDetails)->productName ?? 'N/A',
                'user_count' => $items->count(),
                'estimated_amount' => $items->count() * (optional($items->first()->productDetails)->priceB2C ?? 0),
            ];
        })->filter(function ($item) {
            return $item['product_name'] !== 'N/A'; // Remove products with no name
        })->values();
    
        return response()->json(['data' => $groupedItems]);
    }
    
    public function CartData($product_id = null)
    {
        $query = CartItem::with(['cart.userDetails', 'productDetails'])->orderBy('id', 'desc');
    
        if ($product_id) {
            $query->where('product_id', $product_id);
        }
    
        // Fetch data
        $CartItems = $query->get();
    
        // Get product name from the first cart item
        $productName = $CartItems->isNotEmpty()
            ? optional($CartItems->first()->productDetails)->productName
            : 'N/A';
    
        return view('admin.cart.list', compact('CartItems', 'productName'));
    }
    

    public function cartOnStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cartId' => 'required|exists:cart_items,id', // Change 'carts' to 'cart_items'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $cartItem = CartItem::findOrFail($request->cartId); // Find CartItem instead of Cart
            $cartItem->follow_off = $request->status;
            $cartItem->save();
    
            $statusMessages = [
                0 => 'inactive',
                1 => 'active',
            ];
    
            return response()->json([
                'success' => true,
                'message' => 'Cart item status updated successfully',
                'data' => [
                    'follow_off' => $cartItem->follow_off,
                    'status_text' => $statusMessages[$cartItem->follow_off] ?? 'unknown'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteCart(Request $request)
    {
        $cartItemId = $request->cartId;  // This is actually CartItem ID, not Cart ID
        $cartItem = CartItem::find($cartItemId);

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Cart item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }
    }


    public function massDeleteCart(Request $request)
    {
        $cartItemIds = $request->cartIds; // This should contain an array of CartItem IDs
        $deletedCount = CartItem::whereIn('id', $cartItemIds)->delete();
    
        if ($deletedCount > 0) {
            return response()->json(['message' => 'Selected cart items deleted successfully.']);
        } else {
            return response()->json(['message' => 'No cart items found to delete.'], 404);
        }
    }
    
}

