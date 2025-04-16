<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activations;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Certification;
use App\Models\Couriertype;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function index(Request $req)
    {
        $userId = null;

        if (Auth::guard('euser')->check()) {
            $euser = Auth::guard('euser')->user();
            $userId = $euser->id;
        }
        $ip = $req->getClientIp();

        if ($userId) {
            $cart = Cart::where("userId", $userId)->first();
        } else {
            $cart = Cart::where("ip", $ip)->first();
        }

        // Fetch cart items with product details
        $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();

        foreach ($cartItems as $key => $value) {
            $activation = Activations::find($value->productDetails->activationId);
            $certification = Certification::find($value->productDetails->certificationId);
            $value['productDetails']['activation'] = $activation;
            $value['productDetails']['certification'] = $certification;
        }

        // Initialize variables for subtotal and delivery price
        $totalDelPrice = 0;
        $subtotal = 0;

        // Flag to check if an item with courierTypeId of 2 has been found
        $foundCourierTypeId2 = false;

        // Calculate subtotal
        $subtotal = $cartItems->sum(function ($item) use (&$totalDelPrice, &$foundCourierTypeId2) {
            $product = Product::find($item->product_id);
            $courierType = Couriertype::find($item->productDetails->courierTypeId);

            // Determine delivery price
            if ($courierType) {
                $deliveryPrice = $courierType->courier_price; 

                // Check if the current item has courierTypeId of 2
                if ($courierType->id == 2) {
                    if (!$foundCourierTypeId2) {
                        // If this is the first item with courierTypeId 2, keep the delivery price
                        $foundCourierTypeId2 = true; // Set the flag
                    } else {
                        // If another item with courierTypeId 2 is found, set delivery price to 0
                        $deliveryPrice = 0;
                    }
                } elseif ($courierType->id == 3 || ($courierType->id == 4 && $product->categoryId != 1)) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else {
                $deliveryPrice = 0; // Default to 0 if no courier type is found
            }

            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;

            // Calculate total price based on product category
            if ($product->categoryId != 1) {
                $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            } else {
                $item->totalPrice = ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                return ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
            }
        });

        return view('user.cart.list', compact('cartItems', 'subtotal', 'totalDelPrice'));
    }



    public function viewCart(Request $req)
    {
        $userId = null;

        if (Auth::guard('euser')->check()) {
            $euser = Auth::guard('euser')->user();
            $userId = $euser->id;
        }
        $ip = $req->getClientIp();

        if ($userId) {
            $cart = Cart::where("userId", $userId)->first();
        } else {
            $cart = Cart::where("ip", $ip)->first();
        }
        $cartItems = $cart->items;
        $totalDelPrice = 0;
        $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);
            $product = Product::find($item->product_id);

            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4  && $product->categoryId != 1) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else
                $deliveryPrice = 0;
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
        $totalCartItems = CartItem::where("cart_id", $cart->id)->count();

        return response()->json([
            'totalCartItems' => $totalCartItems,
            'subtotal' => $subtotal,
            'cart' => $cart,
            'cartItems' => $cartItems,
            'totalDelPrice' => $totalDelPrice,
        ]);
    }
    public function addToCart(Request $req)
    {
        $userId = null;

        if (Auth::guard('euser')->check()) {
            $euser = Auth::guard('euser')->user();
            $userId = $euser->id;
        }
        $ip = $req->getClientIp();
        $product_id = $req->product_id;
        $quantity = $req->quantity;


        // Check if the cart is associated with an IP or user ID
        if ($userId) {
            $cart = Cart::where("userId", $userId)->first();
            if (!$cart) {
                $cart = new Cart();
                $cart->userId = $userId;
                $cart->save();
            }
        } else {
            $cart = Cart::where("ip", $ip)->first();
            if (!$cart) {
                $cart = new Cart();
                $cart->ip = $ip;
                $cart->save();
            }
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = $product_id;
            $cartItem->quantity = $quantity;
            $cartItem->is_act_selected = $req->isActive;
            $cartItem->is_cert_selected = $req->isCert;
            $cartItem->ring_type = $req->ring_type;
            $cartItem->ring_price = $req->ring_price;
            if ($req->isActive == 1) {
                $product = Product::find($product_id);
                if ($product->activationId != 1 || $product->activationId != 2) {
                    $activation = Activations::find($product->activationId);
                    $cartItem->activation = $activation->amount == "Free" ? 0 : $activation->amount;
                    $cartItem->activation_name = $req->activation_name;
                    $cartItem->activation_gotra = $req->activation_gotra;
                    $cartItem->activation_dob = $req->activation_dob;
                } else{
                    $cartItem->activation = 0;
                }
            }
            if ($req->isCert == 1) {
                $product = Product::find($product_id);
                if ($product->certificationId != 1 || $product->certificationId != 2) {
                    $activation = Certification::find($product->certificationId);
                    $cartItem->certificate = $activation->amount == "Free" ? 0 : $activation->amount;
                } else
                    $cartItem->certificate = 0;
            }
            $cartItem->save();
        }

        // Get the total number of items in the cart
        $totalCartItems = CartItem::where("cart_id", $cart->id)->count();
        $cart = Cart::find($cartItem->cart_id);
        $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();
        $subtotal =   $cartItems->sum(function ($item) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);
            $product = Product::find($item->product_id);
            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4 && $product->categoryId != 1) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else
                $deliveryPrice = 0;

            if ($product->categoryId != 1) {
                $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            } else {
                $item->totalPrice = ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                return ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
            }
        });

        return response()->json([
            'subtotal' => $subtotal,
            'totalCartItems' => $totalCartItems,
            'message' => "Successfully added to cart"
        ]);
    }

    public function removeFromCart(Request $req)
    {
        $cartItemId = $req->cartItemId;
        $cartItem = CartItem::find($cartItemId);
        $cartId = $cartItem->cart_id;

        if ($cartItem->delete()) {
            $totalCartItems = CartItem::where("cart_id", $cartId)->count();
        }

        $cart = Cart::find($cartId);
        $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();
        $totalDelPrice = 0;
        $subtotal = 0;

        // Flag to check if an item with courierTypeId of 2 has been found
        $foundCourierTypeId2 = false;

        // Calculate subtotal
        $subtotal = $cartItems->sum(function ($item) use (&$totalDelPrice, &$foundCourierTypeId2) {
            $product = Product::find($item->product_id);
            $courierType = Couriertype::find($item->productDetails->courierTypeId);

            // Determine delivery price
            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;

                // Check if the current item has courierTypeId of 2
                if ($courierType->id == 2) {
                    if (!$foundCourierTypeId2) {
                        // If this is the first item with courierTypeId 2, keep the delivery price
                        $foundCourierTypeId2 = true; // Set the flag
                    } else {
                        // If another item with courierTypeId 2 is found, set delivery price to 0
                        $deliveryPrice = 0;
                    }
                } elseif ($courierType->id == 3 || ($courierType->id == 4 && $product->categoryId != 1)) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else {
                $deliveryPrice = 0; // Default to 0 if no courier type is found
            }

            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;

            // Calculate total price based on product category
            if ($product->categoryId != 1) {
                $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            } else {
                $item->totalPrice = ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                return ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
            }
        });

        $total = $subtotal - $totalDelPrice;

        return response()->json([
            'subtotal' => $subtotal,
            'total' => $total,
            'totalCartItems' => $totalCartItems,
            'totalDelPrice' => $totalDelPrice,
            'message' => "Successfully removed from cart"
        ]);
    }

    public function changeQuantity(Request $req)
    {
        $cartItemId = $req->cartItemId;
        $quantity = $req->quantity;
        $cartItem = CartItem::find($cartItemId);
        $cartItem->quantity = $quantity;
        $itemTotal = 0;
        $itemDeliveryPrice = 0;

        if ($cartItem->save()) {
            $cart = Cart::find($cartItem->cart_id);
            $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();

            // Initialize variables for subtotal and delivery price
            $totalDelPrice = 0;
            $subtotal = 0;

            // Flag to check if an item with courierTypeId of 2 has been found
            $foundCourierTypeId2 = false;

            // Calculate subtotal
            $subtotal = $cartItems->sum(function ($item) use (&$totalDelPrice, &$cartItemId, &$itemTotal, &$itemDeliveryPrice, &$foundCourierTypeId2) {
                $product = Product::find($item->product_id);
                $courierType = Couriertype::find($item->productDetails->courierTypeId);

                // Determine delivery price
                if ($courierType) {
                    $deliveryPrice = $courierType->courier_price;

                    // Check if the current item has courierTypeId of 2
                    if ($courierType->id == 2) {
                        if (!$foundCourierTypeId2) {
                            // If this is the first item with courierTypeId 2, keep the delivery price
                            $foundCourierTypeId2 = true; // Set the flag
                        } else {
                            // If another item with courierTypeId 2 is found, set delivery price to 0
                            $deliveryPrice = 0;
                        }
                    } elseif ($courierType->id == 3 || ($courierType->id == 4 && $product->categoryId != 1)) {
                        $deliveryPrice = $deliveryPrice * $item->quantity;
                    }
                } else {
                    $deliveryPrice = 0; // Default to 0 if no courier type is found
                }

                $totalDelPrice += $deliveryPrice;
                $item->deliveryPrice = $deliveryPrice;

                // Calculate total price based on product category
                if ($product->categoryId != 1) {
                    $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                    if ($item->id == $cartItemId) {
                        $itemTotal = $item->totalPrice;
                        $itemDeliveryPrice = $deliveryPrice;
                    }
                    return $item->totalPrice;
                } else {
                    $item->totalPrice = ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                    if ($item->id == $cartItemId) {
                        $itemTotal = $item->totalPrice;
                        $itemDeliveryPrice = $deliveryPrice;
                    }
                    return $item->totalPrice;
                }
            });

            $total = $subtotal - $totalDelPrice;

            return response()->json([
                'message' => "Successfully updated the cart",
                'totalDelPrice' => $totalDelPrice,
                'subtotal' => $subtotal,
                'total' => $total,
                'itemTotal' => $itemTotal,
                'itemDeliveryPrice' => $itemDeliveryPrice,
            ]);
        }
    }

    public function changeAddSettings(Request $req)
    {

        $cartItemId = $req->cartItemId;
        $activation = $req->activation;
        $certificate = $req->certificate;
        $cartItem = CartItem::find($cartItemId);
        $cartItem->is_act_selected = $req->isActive;
        $cartItem->is_cert_selected = $req->isCert;
        $itemTotal = 0;
        $itemDeliveryPrice = 0;

        if ($req->isActive == 1) {
            $cartItem->activation = $activation ==  "Free" ? 0 : $activation;
        } else
            $cartItem->activation = 0;
        if ($req->isCert == 1) {
            $cartItem->certificate = $certificate == "Free" ? 0 : $certificate;
        } else
            $cartItem->certificate = 0;



        if ($cartItem->save()) {
            $cart = Cart::find($cartItem->cart_id);
            $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();
            $totalDelPrice = 0;
            $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice, &$cartItem, &$itemTotal, &$itemDeliveryPrice) {
                $courierType = Couriertype::find($item->productDetails->courierTypeId);
                $product = Product::find($item->product_id);
                if ($courierType) {
                    $deliveryPrice = $courierType->courier_price;
                    if ($courierType->id == 3 || $courierType->id == 4 && $product->categoryId != 1) {
                        $deliveryPrice = $deliveryPrice * $item->quantity;
                    }
                } else
                    $deliveryPrice = 0;
                $totalDelPrice += $deliveryPrice;
                $item->deliveryPrice = $deliveryPrice;

                // $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;

                if ($product->categoryId != 1) {
                    $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                    if ($item->id == $cartItem->id) {
                        $itemTotal = $item->totalPrice;
                        $itemDeliveryPrice = $deliveryPrice;
                    }
                    return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                } else {
                    $item->totalPrice = ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                    if ($item->id == $cartItem->id) {
                        $itemTotal = $item->totalPrice;
                        $itemDeliveryPrice = $deliveryPrice;
                    }
                    return ($item->productDetails->priceB2C) * $item->quantity + $deliveryPrice + $item->activation + $item->certificate;
                }
            });
            $total = $subtotal - $totalDelPrice;

            return response()->json([
                'message' => "Succesfully updated the cart",
                'subtotal' => $subtotal,
                'total' => $total,
                'totalDelPrice' => $totalDelPrice,
                'itemDeliveryPrice' => $itemDeliveryPrice,
                'itemTotal' => $itemTotal,
            ]);
        }
    }

        public function destroyCoupon(Request $request)
        {
            // Destroy the coupon session
            session()->forget('coupon');

            // Return a response indicating success
            return response()->json(['message' => 'Coupon removed successfully']);
        }
}
