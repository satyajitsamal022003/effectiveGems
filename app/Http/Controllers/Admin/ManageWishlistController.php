<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManageWishlistController extends Controller
{
    public function aWishlistData(Request $request)
    {
        return view('admin.wishlist.frontlist');
    }

    public function getWishlistData()
    {
        $Wishlist = Wishlist::with(['userDetails', 'productDetails'])->orderBy('id', 'desc')->get();

        $wishlistData = $Wishlist->map(function ($wishlistItem) {
            return [
                'product_id' => $wishlistItem->product_id,
                'product_name' => optional($wishlistItem->productDetails)->productName ?? 'N/A',
                'user_count' => Wishlist::where('product_id', $wishlistItem->product_id)->count(),
                'estimated_amount' => Wishlist::where('product_id', $wishlistItem->product_id)->count() * (optional($wishlistItem->productDetails)->priceB2C ?? 0)
            ];
        })->unique('product_id')->values(); // Ensure unique products

        return response()->json(['data' => $wishlistData]);
    }



    public function WishlistData($product_id = null)
    {
        $query = Wishlist::with(['userDetails', 'productDetails'])->orderBy('id', 'desc');

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        // Fetch data
        $Wishlist = $query->get();

        // Get product name from the first wishlist entry
        $productName = $Wishlist->first()->productDetails->productName ?? 'N/A';

        return view('admin.wishlist.list', compact('Wishlist', 'productName'));
    }



    public function wishlistOnStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'wishlistId' => 'required|exists:wishlists,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $wishlist = Wishlist::findOrFail($request->wishlistId);
            $wishlist->follow_off = $request->status;
            $wishlist->save();

            $statusMessages = [
                0 => 'inactive',
                1 => 'active',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Is followed updated successfully',
                'data' => [
                    'follow_off' => $wishlist->follow_off,
                    'status_text' => $statusMessages[$wishlist->follow_off] ?? 'unknown'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Is followed update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Is followed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteWishlist(Request $request)
    {
        $wishlistId = $request->wishlistId;
        Wishlist::where('id', $wishlistId)->delete();
        return response()->json(['message' => 'Wishlist item deleted successfully.']);
    }

    public function massDeleteWishlist(Request $request)
    {
        $wishlistIds = $request->wishlistIds;
        Wishlist::whereIn('id', $wishlistIds)->delete();
        return response()->json(['message' => 'Selected wishlist items deleted successfully.']);
    }
}
