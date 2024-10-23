<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activations;
use App\Models\Cart;
use App\Models\Certification;
use App\Models\Couriertype;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\State;
use Illuminate\Http\Request;
use Razorpay\Api\Api;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        //
        $ip = $req->getClientIp();

        if ($ip) {
            $cart = Cart::where("ip", $ip)->first();
        }

        // Fetch cart items with product details
        $cartItems = $cart ? $cart->items()->with('productDetails')->get() : collect(); // Eager load productDetails
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
        $states = State::all();
        return view('user.checkout.index', compact('states', 'cartItems', 'subtotal', 'total', 'totalDelPrice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $ip = $request->getClientIp();
        $order = new Order();
        $order->ip = $ip;
        $order->email = $request->email;
        $order->firstName = $request->firstName;
        $order->middleName = $request->middleName;
        $order->lastName = $request->lastName;
        $order->amount = $request->amount;
        $order->phoneNumber = $request->phoneNumber;
        $order->country = $request->country;
        $order->state = $request->state;
        $order->city = $request->city;
        $order->zipcode = $request->zipcode;
        $order->landmark = $request->landmark;
        $order->appartment = $request->appartment;
        $order->address = $request->address;
        if ($request->sameAddress) {
            $order->billingfirstName = $request->billingfirstName;
            $order->billingmiddleName = $request->billingmiddleName;
            $order->billinglastName = $request->billinglastName;
            $order->billingamount = $request->billingamount;
            $order->billingphoneNumber = $request->billingphoneNumber;
            $order->billingcountry = $request->billingcountry;
            $order->billingstate = $request->billingstate;
            $order->billingcity = $request->billingcity;
            $order->billingzipcode = $request->billingzipcode;
            $order->billinglandmark = $request->billinglandmark;
            $order->billingappartment = $request->billingappartment;
            $order->billingaddress = $request->billingaddress;
        }
        $dateTime = new \DateTime("now", new \DateTimeZone("Asia/Kolkata"));
        $order->created_at = $dateTime;
        $order->shippingAmount = $request->shippingAmount;
        $order->sameBillingAddress = $request->sameAddress;
        $order->promoCode = $request->promoCode;
        if ($order->save()) {
            $orderId = $order->id;
            $cart = Cart::where("ip", $ip)->first();
            $cartItems = $cart ? $cart->items()->with('productDetails')->get() : collect();
            $subtotal =   $cartItems->sum(function ($item) use ($request) {
                $courierType = Couriertype::find($item->productDetails->courierTypeId);
                if ($courierType) {
                    $deliveryPrice = $courierType->courier_price;
                    if ($courierType->id == 3 || $courierType->id == 4) {
                        $deliveryPrice = $deliveryPrice * $item->quantity;
                    }
                } else
                    $deliveryPrice = 0;
                    if ($item->productDetails->categoryId == 1) {
                        return ($item->productDetails->priceB2C * $item->quantity) + $item->activation + $item->certificate + $courierType->courier_price;
                    } else {
                return ($item->productDetails->priceB2C + $item->activation + $item->certificate) * $item->quantity + $deliveryPrice;
               }
            });
            foreach ($cartItems as $key => $value) {
                $orderItem = new OrderItem();
                $orderItem->orderId = $orderId;
                $orderItem->proId = $value->product_id;
                $orderItem->quantity = $value->quantity;
                $orderItem->activation = $value->activation;
                $orderItem->certificate = $value->certificate;
                $orderItem->is_act_selected = $value->is_act_selected;
                $orderItem->is_cert_selected = $value->is_cert_selected;
                $orderItem->amount = $value->quantity * $value->productDetails->priceB2B;
                $orderItem->save();
            }
            $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));
            $razorpayOrderData = [
                'receipt'         => 'orderId-' . $orderId, // Your internal order ID as receipt ID
                'amount'          => $subtotal * 100,
                'currency'        => 'INR',
                'payment_capture' => 1
            ];
            try {
                $razorpayOrder = $api->order->create($razorpayOrderData);

                // Get the Razorpay Order ID (this is what needs to be passed to the frontend)
                $razorpayOrderId = $razorpayOrder['id'];

                // Pass $razorpayOrderId to the frontend
                return view('user.checkout.razorpay', compact('razorpayOrderId', 'subtotal', 'orderId'));
            } catch (\Throwable $th) {
                dd($th);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
