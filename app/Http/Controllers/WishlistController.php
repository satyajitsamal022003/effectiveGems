<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all wishlist items for the authenticated user
        $wishlists = Wishlist::where('user_id', Auth::guard('euser')->user()->id)
            ->with('productDetails') // Eager load the related product details
            ->paginate(10);

        // Return the wishlist view with the data
        return view('eusers.mywishlist.list', compact('wishlists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view for adding a new wishlist item
        return view('wishlists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
        ]);

        $userId = Auth::guard('euser')->user()->id;

        // Check if the product already exists in the wishlist
        $existingWishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingWishlistItem) {
            $existingWishlistItem->delete();
            // Return JSON response with error
            return response()->json([
                'status' => 'removed',
                'message' => 'Product removed from wishlist.',
            ]);
        }

        // Add the product to the wishlist
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $validated['product_id'],
        ]);

        // Return a success JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Product added to wishlist successfully.',
        ]);
    }

    public function moveto(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
        ]);

        if (Auth::guard('euser')->check()) {
            $userId = Auth::guard('euser')->id();
            $productId = $request->product_id;

            $cart = Cart::where('userId', $userId)->first();

            if ($cart) {
                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($cartItem) {
                    $cartItem->delete();
                }

                Wishlist::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);

                return response()->json(['message' => 'Product moved to wishlist successfully!']);
            }

            // If no cart is found
            return response()->json(['message' => 'No active cart found'], 404);
        }

        // If user is not logged in
        return response()->json(['message' => 'Please login to add to the wishlist'], 401);
    }





    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist)
    {
        // Check if the authenticated user owns the wishlist item
        if ($wishlist->user_id !== Auth::guard('euser')->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Return a view showing the details of the wishlist item
        return view('wishlists.show', compact('wishlist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wishlist $wishlist)
    {
        // Check if the authenticated user owns the wishlist item
        if ($wishlist->user_id !== Auth::guard('euser')->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Return a view for editing the wishlist item
        return view('wishlists.edit', compact('wishlist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        // Check if the authenticated user owns the wishlist item
        if ($wishlist->user_id !== Auth::guard('euser')->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist->update([
            'product_id' => $validated['product_id'],
        ]);

        // Redirect back to the wishlist index page with a success message
        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $wishlist = Wishlist::find($request->id);
        // Check if the authenticated user owns the wishlist item
        if ($wishlist->user_id !== Auth::guard('euser')->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $wishlist->delete();

        // Redirect back to the wishlist index page with a success message
        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from wishlist successfully.',
        ]);
    }
}
