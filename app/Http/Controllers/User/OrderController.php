<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activations;
use App\Models\Cart;
use App\Models\Certification;
use App\Models\Couriertype;
use App\Models\Euser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\State;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Razorpay\Api\Api;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        //
        $userId = null;

        // Check if the user is authenticated
        if (Auth::guard('euser')->check()) {
            $userId = Auth::guard('euser')->user()->id;
        }

        // Fetch the cart based on IP or userId
        $ip = $req->getClientIp();

        if ($userId) {
            $cart = Cart::where('userId', $userId)->first();
        } else {
            $cart = Cart::where('ip', $ip)->first();
        }

        // if (!$cart && $userId) {
        //     $cart = Cart::where('userId', $userId)->first();
        // }

        // If no cart found, redirect to user index
        if (!$cart) {
            return redirect()->route('user.index');
        }

        $cartId = $cart->id;

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

        $addressdata = UserAddress::where('user_id', $userId)->get();
        return view('user.checkout.index', compact('states', 'cartItems', 'subtotal', 'total', 'totalDelPrice', 'cartId', 'addressdata'));
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
        $userId = null;

        if (Auth::guard('euser')->check()) {
            $euser = Auth::guard('euser')->user();
            $userId = $euser->id;
        }

        $ip = $request->getClientIp();
        $order = new Order();
        if ($userId) {
            $order->userId = $userId;
        } else {
            $order->ip = $ip;
        }

        $usermobile = Euser::where('mobile', $request->phoneNumber)->first();


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
        $order->orderStatus = 'Failed';
        $order->couponApplied = $request->appliedCoupon;
        if ($request->has('save_information') && $userId) {
            $userAddress = new UserAddress();
            $userAddress->user_id = $userId;
            $userAddress->first_name = $request->firstName;
            $userAddress->middle_name = $request->middleName;
            $userAddress->last_name = $request->lastName;
            $userAddress->phone = $request->phoneNumber;
            $userAddress->country_id = $request->country;
            $userAddress->state_id = $request->state;
            $userAddress->city_name = $request->city;
            $userAddress->zip_code = $request->zipcode;
            $userAddress->landmark = $request->landmark;
            $userAddress->apartment = $request->appartment;
            $userAddress->address = $request->address;
            // $userAddress->address_type = $request->input('address_type', 'Home');
            $userAddress->address_type = $request->address_type;
            $userAddress->save();
        }
        if ($order->save()) {
            $orderId = $order->id;
            if ($userId) {

                $cart = Cart::where("userId", $userId)->first();
            } else
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
            $amount = $request->amount * 100;
            $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));
            $razorpayOrderData = [
                'receipt'         => 'orderId-' . $orderId, // Your internal order ID as receipt ID
                'amount'          => $amount,
                'currency'        => 'INR',
                'payment_capture' => 1
            ];
            try {
                $razorpayOrder = $api->order->create($razorpayOrderData);

                session([
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'subtotal' => $subtotal,
                    'order_id' => $orderId,
                ]);

                if (!$userId && !$usermobile) {
                    return response()->json(['otp_required' => true, 'message' => 'Please verify your mobile number.']);
                } else if (!$userId && $usermobile && $usermobile->is_mobile_verified == 0) {
                    return response()->json(['otp_required' => true, 'message' => 'Please verify your mobile number.']);
                } else {
                    return response()->json([
                        'success' => true,
                        'redirect_url' => route('razorpay.order', ['razorpayOrderId' => $razorpayOrder['id'], 'subtotal' => $subtotal, 'orderId' => $orderId])
                    ]);
                    // return response()->json(['success' => true, 'message' => 'Mobile Number Verified successfully.']);
                }
            } catch (\Throwable $th) {
                dd($th);
            }
        }
        return response()->json(['success' => false, 'message' => 'Order creation failed.'], 500);
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

    public function sendOtp(Request $request)
    {
        $request->validate(['mobile' => 'required|digits:10']);

        $mobile = $request->mobile;

        $otp = rand(100000, 999999);

        $apikey = '5xP9YXeSUnRUqqEw';
        $senderid = 'EFGEMS';
        $templateid = '110xxxxxxxxxx592';
        $message_content = urlencode("Dear User, Your OTP For Login is $otp. Regards -Effective Gems");
        $url = "http://text2india.store/vb/apikey.php?apikey=$apikey&senderid=$senderid&number=$mobile&message=$message_content&templateid=$templateid";

        try {
            $response = file_get_contents($url);

            if ($response) {
                session(['otp' => $otp, 'mobile' => $mobile, 'otp_created_at' => now(),]);

                return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
            }

            return response()->json(['success' => false, 'message' => 'Failed to send OTP.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error occurred: ' . $e->getMessage()]);
        }
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        if(session()->has('order_id')){
            $order_id = session('order_id');
            $orderdata = Order::where('id',$order_id)->update(['phoneNumber'=>$request->mobile]);
        }


        $otpCreatedAt = session('otp_created_at');
        if (session('otp') == $request->otp && session('mobile') == $request->mobile) {
            if (now()->diffInMinutes($otpCreatedAt) > 5) {
                return response()->json(['success' => false, 'message' => 'OTP has expired.']);
            }
            $user = Euser::where('mobile', $request->mobile)->first();

            if ($user) {
                $user->update(['is_mobile_verified' => 1]);
                Auth::guard('euser')->login($user);
                // return response()->json(['success' => true, 'message' => 'Mobile Number Verified.']);
                if (session()->has('razorpay_order_id') && session()->has('subtotal') && session()->has('order_id')) {
                    return response()->json([
                        'success' => true,
                        'redirect_url' => route('razorpay.order', [
                            'razorpayOrderId' => session('razorpay_order_id'),
                            'subtotal' => session('subtotal'),
                            'orderId' => session('order_id')
                        ])
                    ]);
                }
            }


            $randomPassword = Str::random(8);
            $mobile = $request->mobile;

            $newUser = Euser::create([
                'mobile' => $request->mobile,
                'password' => Hash::make($randomPassword),
                'is_mobile_verified' => 1
            ]);

            $ip = $request->getClientIp();

            Cart::where('ip', $ip)
                ->update(['userId' => $newUser->id, 'ip' => null]);


            $apikey = '5xP9YXeSUnRUqqEw';
            $senderid = 'EFGEMS';
            $templateid = '110xxxxxxxxxx592';
            $message = "Welcome! Your account has been created. Mobile: $mobile, Password: $randomPassword";
            $message_content = urlencode($message);
            $url = "http://text2india.store/vb/apikey.php?apikey=$apikey&senderid=$senderid&number=$mobile&message=$message_content&templateid=$templateid";

            try {
                $response = file_get_contents($url);

                if ($response) {
                    \Log::info("SMS sent successfully to {$request->mobile}: Response = $response");
                } else {
                    \Log::error("SMS sending failed for {$request->mobile}: No response received.");
                }
            } catch (\Exception $e) {
                \Log::error("Error sending SMS to {$request->mobile}: " . $e->getMessage());
            }

            if (session()->has('razorpay_order_id') && session()->has('subtotal') && session()->has('order_id')) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('razorpay.order', [
                        'razorpayOrderId' => session('razorpay_order_id'),
                        'subtotal' => session('subtotal'),
                        'orderId' => session('order_id')
                    ])
                ]);
            }


            Auth::guard('euser')->login($newUser);
            return response()->json(['success' => false, 'message' => 'Order details missing.'], 500);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP.']);
    }
}
