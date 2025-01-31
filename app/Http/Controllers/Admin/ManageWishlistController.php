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
    public function WishlistData()
    {
        $Wishlist = Wishlist::with(['userDetails', 'productDetails'])->orderBy('id', 'desc')->get();
        return view('admin.wishlist.list', compact('Wishlist'));
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
}
