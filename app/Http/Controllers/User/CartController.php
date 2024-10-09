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

class CartController extends Controller
{
    //
    public function index(Request $req)
    {
        $ip = $req->getClientIp();

        if ($ip) {
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
        // Calculate subtotal
        $totalDelPrice = 0;
        $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);

            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else
                $deliveryPrice = 0;
            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;
            $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
        });


        return view('user.cart.list', compact('cartItems', 'subtotal', 'totalDelPrice'));
    }



    public function viewCart(Request $req)
    {
        $ip = $req->getClientIp();
        $userId = $req->userId;

        if ($ip) {
            $cart = Cart::where("ip", $ip)->first();
        } else {
            $cart = Cart::where("userId", $userId)->first();
        }
        $cartItems = $cart->items;
        $totalDelPrice = 0;
        $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);

            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else
                $deliveryPrice = 0;
            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;
            $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
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
        $ip = $req->getClientIp();
        $userId = $req->userId;
        $product_id = $req->product_id;
        $quantity = $req->quantity;


        // Check if the cart is associated with an IP or user ID
        if ($ip) {
            $cart = Cart::where("ip", $ip)->first();
            if (!$cart) {
                $cart = new Cart();
                $cart->ip = $ip;
                $cart->save();
            }
        } else {
            $cart = Cart::where("userId", $userId)->first();
            if (!$cart) {
                $cart = new Cart();
                $cart->userId = $userId;
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
            if ($req->isActive == 1) {
                $product = Product::find($product_id);
                if ($product->activationId != 1 || $product->activationId != 2) {
                    $activation = Activations::find($product->activationId);
                    $cartItem->activation = $activation->amount;
                } else
                    $cartItem->activation = 0;
            }
            if ($req->isCert == 1) {
                $product = Product::find($product_id);
                if ($product->certificationId != 1 || $product->certificationId != 2) {
                    $activation = Certification::find($product->certificationId);
                    $cartItem->certificate = $activation->amount;
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
            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else
                $deliveryPrice = 0;
            return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
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
        if ($cartItem->delete())
            $totalCartItems = CartItem::where("cart_id", $cartId)->count();

        $cart = Cart::find($cartItem->cart_id);
        $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();
        $totalDelPrice = 0;

        $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice) {
            $courierType = Couriertype::find($item->productDetails->courierTypeId);

            if ($courierType) {
                $deliveryPrice = $courierType->courier_price;
                if ($courierType->id == 3 || $courierType->id == 4) {
                    $deliveryPrice = $deliveryPrice * $item->quantity;
                }
            } else
                $deliveryPrice = 0;
            $totalDelPrice += $deliveryPrice;
            $item->deliveryPrice = $deliveryPrice;
            $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
        });
        $total = $subtotal - $totalDelPrice;
        return response()->json([
            'subtotal' => $subtotal,
            'total' => $total,
            'totalCartItems' => $totalCartItems,
            'totalDelPrice' => $totalDelPrice,
            'message' => "Succesfully removed from cart"
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
            $totalDelPrice = 0;
            $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice, &$cartItemId, &$itemTotal,&$itemDeliveryPrice) {
                $courierType = Couriertype::find($item->productDetails->courierTypeId);

                if ($courierType) {
                    $deliveryPrice = $courierType->courier_price;
                    if ($courierType->id == 3 || $courierType->id == 4) {
                        $deliveryPrice = $deliveryPrice * $item->quantity;
                    }
                } else
                    $deliveryPrice = 0;
                $totalDelPrice += $deliveryPrice;
                $item->deliveryPrice = $deliveryPrice;

                $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                if ($item->id == $cartItemId) {
                    $itemTotal = $item->totalPrice;
                    $itemDeliveryPrice = $deliveryPrice;
                }

                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            });
            $total = $subtotal - $totalDelPrice;
            return response()->json([
                'message' => "Succesfully updated the cart",
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
            $cartItem->activation = $activation;
        } else
            $cartItem->activation = 0;
        if ($req->isCert == 1) {
            $cartItem->certificate = $certificate;
        } else
            $cartItem->certificate = 0;



        if ($cartItem->save()) {
            $cart = Cart::find($cartItem->cart_id);
            $cartItems = $cart ? $cart->items()->with(['productDetails.activation', 'productDetails.certificate'])->get() : collect();
            $totalDelPrice = 0;
            $subtotal =   $cartItems->sum(function ($item) use (&$totalDelPrice, &$cartItem, &$itemTotal,&$itemDeliveryPrice) {
                $courierType = Couriertype::find($item->productDetails->courierTypeId);

                if ($courierType) {
                    $deliveryPrice = $courierType->courier_price;
                    if ($courierType->id == 3 || $courierType->id == 4) {
                        $deliveryPrice = $deliveryPrice * $item->quantity;
                    }
                } else
                    $deliveryPrice = 0;
                $totalDelPrice += $deliveryPrice;
                $item->deliveryPrice = $deliveryPrice;

                $item->totalPrice = ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
                if ($item->id == $cartItem->id) {
                    $itemTotal = $item->totalPrice;
                    $itemDeliveryPrice = $deliveryPrice;
                }
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
            });
            $total = $subtotal - $totalDelPrice;

            return response()->json([
                'message' => "Succesfully updated the cart",
                'subtotal' => $subtotal,
                'total' => $total,
                'totalDelPrice' => $totalDelPrice,
                'itemDeliveryPrice' => $itemDeliveryPrice,
            ]);
        }
    }
}
