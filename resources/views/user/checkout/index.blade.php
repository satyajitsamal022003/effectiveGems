@extends('user.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container checkout_style as_padderBottom40">
    <div class="cart-body section-padding">

        <form method="POST" id="orderForm" class="row">
            @csrf
            <div class="col-lg-7 col-md-7 col-12">
                <div class="checkout-main-form">
                    <input type="hidden" name="amount" class="amount" value="{{ $total }}">
                    <!--customer area start-->
                    @if (!Auth::guard('euser')->check())
                    <div class="customer-email">
                        <div class="panel-info mt-0">
                            <div class="panel-heading">Contact</div>
                            {{-- <a href="login.html" class="edit-after-submit">Login</a> --}}
                        </div>
                        <div class="row justify-content-center inline-shipping">
                            <div class="col-lg-12 col-12">
                                <!--guest login area start-->
                                <div class="form-group mb-0 guest-login">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <label for="email_3">Email Address*</label>
                                            <div class="form_item">
                                                <input type="email" required name="email" id="email_3"
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-check-box">
                                        <input type="checkbox" id="subscribe_check" name="sub_check" value="">
                                        <label for="subscribe_check">Email me with news and offers</label>
                                    </div>
                                </div>
                                <!--guest login area end-->
                            </div>
                        </div>
                    </div>
                    @endif
                    <!--customer area end-->

                    <!--shipping area start-->
                    <div class="shipping-area">
                        <div class="panel-info">
                            <div class="panel-heading">Shipping Address</div>
                        </div>
                        @if (Auth::guard('euser')->check())
                        <a href="javascript:;" class="add-new-address" onclick="toggleNewAddressForm()">
                            <i class="fa-light fa-plus"></i> Add New Address
                        </a>
                        @endif
                        <!--if login then show option start-->
                        <div class="login-option mt-2">
                            <div class="row">
                                @foreach ($addressdata as $addressdatas)
                                <div class="col-lg-4 col-12">
                                    <div class="login-option-list">
                                        <a href="javascript:;" class="edit-btn" data-id="{{ $addressdatas->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <label for="address-option-{{ $addressdatas->id }}">
                                            <input type="radio" id="address-option-{{ $addressdatas->id }}"
                                                name="address-option-radio" data-id="{{ $addressdatas->id }}"
                                                data-first_name="{{ $addressdatas->first_name }}"
                                                data-middle_name="{{ $addressdatas->middle_name }}"
                                                data-last_name="{{ $addressdatas->last_name }}"
                                                data-phone="{{ $addressdatas->phone }}"
                                                data-address="{{ $addressdatas->address }}"
                                                data-apartment="{{ $addressdatas->apartment }}"
                                                data-landmark="{{ $addressdatas->landmark }}"
                                                data-city="{{ $addressdatas->city_name }}"
                                                data-state_id="{{ $addressdatas->state_id }}"
                                                data-zipcode="{{ $addressdatas->zip_code }}"
                                                data-country="{{ $addressdatas->country_id }}"
                                                data-address_type="{{ $addressdatas->address_type }}">
                                            <h4>{{ $addressdatas->first_name }} {{ $addressdatas->middle_name }}
                                                {{ $addressdatas->last_name }}
                                            </h4>
                                            <p><i class="fa-solid fa-phone"></i> {{ $addressdatas->phone }}</p>
                                            <hr>
                                            <p>{{ $addressdatas->address }} - {{ $addressdatas->zip_code }},
                                                {{ $addressdatas->city_name }},
                                                {{ $addressdatas->state->stateName }},
                                                {{ $addressdatas->country_id == 1 ? 'India' : 'N/A' }}
                                            </p>
                                        </label>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        <!--if login then show option end-->
                        @if (!Auth::guard('euser')->check())
                        <div class="new-address">
                            <div class="row justify-content-center inline-shipping">
                                <div class="col-lg-12 col-12">
                                    <div class="shipping-section">
                                        <div method="post" action="">
                                            <div class="form-group col-lg-6 col-12">
                                                <div class="gender-area">
                                                    <label for="f-name">Address Type:</label>
                                                    <div class="d-flex">
                                                        <label><input type="radio" class="input-radio"
                                                                name="address_type" checked="" value="1">
                                                            Home</label>
                                                        <label><input type="radio" class="input-radio"
                                                                name="address_type" value="2">
                                                            Work</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-12">
                                                <label for="f-name">Country/Region:</label>
                                                <select name="country" required class="form-select form-control"
                                                    id="f-name" aria-label="Default select example">
                                                    <option value="1" selected>India</option>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="f-name">First Name:</label>
                                                        <input type="text" required name="firstName"
                                                            id="f-name" class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="f-name">Middle Name:</label>
                                                        <input type="text" name="middleName" id="f-name"
                                                            class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="l-name">Last Name:</label>
                                                        <input type="text" required name="lastName"
                                                            id="l-name" class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <label for="address_1">Address:</label>
                                                        <input name="address" required type="text"
                                                            id="address_2" class="form-control"
                                                            value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <label for="address_2">Apartment/Suite/Building
                                                            (Optional)</label>
                                                        <input name="appartment" type="text" id="address_2"
                                                            class="form-control" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="landmark">Landmark:</label>
                                                        <input name="landmark" required type="text"
                                                            id="landmark" class="form-control"
                                                            value="" />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="city-name">City:</label>
                                                        <input name="city" required type="text"
                                                            id="city-name" class="form-control"
                                                            value="" />
                                                    </div>
                                                    <div class="col-lg-8 col-md-6 col-12">
                                                        <label for="state-name">State:</label>
                                                        <select name="state" required
                                                            class="form-select form-control" id="state-name"
                                                            aria-label="Default select example">
                                                            <option selected disabled>Choose State</option>
                                                            @foreach ($states as $state)
                                                            <option value="{{ $state->id }}">
                                                                {{ $state->stateName }}
                                                            </option>
                                                            @endforeach

                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="postal-code">Zip / Postal Code:</label>
                                                        <input name="zipcode" required type="number"
                                                            id="postal-code" class="form-control"
                                                            value="" />
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-6 col-12">
                                                        <label for="p-no">Phone Number:</label>
                                                        <input required type="tel"
                                                            maxlength="10" name="phoneNumber" id="phoneNumber"
                                                            class="form-control" value="" />
                                                        <span id="mobileError" class="text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="mt-2 flex_box">
                                                    <input type="checkbox" id="billing_information" name="billing_1"
                                                        value="">
                                                    <label for="billing_information">Save this information for next
                                                        time</label>
                                                </div> --}}
                                        <!-- <div class="mt-0 flex_box">
                                            <input type="checkbox" id="billing_same_address" name="billing_1"
                                                value="">
                                            <label for="billing_same_address">My billing address is the same as my
                                                shipping
                                                address.</label>
                                        </div> -->

                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                        @if (Auth::guard('euser')->check())
                        <div class="new-address login-address" style="display: none">
                            <div class="row justify-content-center inline-shipping">
                                <div class="col-lg-12 col-12">
                                    <div class="shipping-section">
                                        <div method="post" action="">
                                            <div class="form-group col-lg-6 col-12">
                                                <div class="gender-area">
                                                    <label for="f-name">Address Type:</label>
                                                    <div class="d-flex">
                                                        <label><input type="radio" class="input-radio"
                                                                name="address_type" checked=""
                                                                value="1">
                                                            Home</label>
                                                        <label><input type="radio" class="input-radio"
                                                                name="address_type" value="2">
                                                            Work</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-12">
                                                <label for="f-name">Country/Region:</label>
                                                <select name="country" required class="form-select form-control"
                                                    id="f-name" aria-label="Default select example">
                                                    <option value="1" selected>India</option>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="f-name">First Name:</label>
                                                        <input type="text" required name="firstName"
                                                            id="f-name" class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="f-name">Middle Name:</label>
                                                        <input type="text" name="middleName" id="f-name"
                                                            class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="l-name">Last Name:</label>
                                                        <input type="text" required name="lastName"
                                                            id="l-name" class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <label for="address_1">Address:</label>
                                                        <input name="address" required type="text"
                                                            id="address_2" class="form-control"
                                                            value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <label for="address_2">Apartment/Suite/Building
                                                            (Optional)</label>
                                                        <input name="appartment" type="text" id="address_2"
                                                            class="form-control" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="landmark">Landmark:</label>
                                                        <input name="landmark" required type="text"
                                                            id="landmark" class="form-control"
                                                            value="" />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="city-name">City:</label>
                                                        <input name="city" required type="text"
                                                            id="city-name" class="form-control"
                                                            value="" />
                                                    </div>
                                                    <div class="col-lg-8 col-md-6 col-12">
                                                        <label for="state-name">State:</label>
                                                        <select name="state" required
                                                            class="form-select form-control" id="state-name"
                                                            aria-label="Default select example">
                                                            <option selected disabled>Choose State</option>
                                                            @foreach ($states as $state)
                                                            <option value="{{ $state->id }}">
                                                                {{ $state->stateName }}
                                                            </option>
                                                            @endforeach

                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="postal-code">Zip / Postal Code:</label>
                                                        <input name="zipcode" required type="number"
                                                            id="postal-code" class="form-control"
                                                            value="" />
                                                    </div>

                                                </div>
                                            </div>
                                            @if (Auth::guard('euser')->check())
                                            <input type="hidden" required type="tel"
                                                maxlength="10" name="phoneNumber" id="p-no"
                                                class="form-control" value="" />
                                            @endif
                                            @if (!Auth::guard('euser')->check())
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-6 col-12">
                                                        <label for="p-no">Phone Number:</label>
                                                        <input required type="tel"
                                                            maxlength="10" name="phoneNumber" id="p-no"
                                                            class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="mt-2 flex_box save-address">
                                            <input type="checkbox" id="billing_information"
                                                name="save_information" value="1">
                                            <label for="billing_information">Save this information for next
                                                time</label>
                                        </div>
                                        <!-- <div class="mt-0 flex_box">
                                            <input type="checkbox" id="billing_same_address" name="billing_1"
                                                value="">
                                            <label for="billing_same_address">My billing address is the same as my
                                                shipping
                                                address.</label>
                                        </div> -->

                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!--shipping area end-->

                    <!--billing area start-->
                    <div class="billing-area mt-3">
                        <div class="panel-info">
                            <div class="panel-heading">Billing Address</div>
                        </div>
                        <div class="inline-shipping pt-0 pb-0">
                            <div class="radio-check-box">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sameAddress" value="1"
                                        id="same_address" onchange="$('.billingAddress').hide()" checked>
                                    <label class="form-check-label" for="same_address">
                                        Same as shipping address
                                    </label>
                                </div>

                                <div class="form-check mt-0">
                                    <input class="form-check-input" onchange="$('.billingAddress').show()"
                                        value="0" type="radio" name="sameAddress" id="different_address">
                                    <label class="form-check-label" for="different_address">
                                        Use a different billing address
                                    </label>
                                </div>
                            </div>

                            <!--after click Use a different billing address then show this section-->
                            <div class="shipping-section billingAddress" style="display: none">
                                <div class="form-area">
                                    <div method="post" action="">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-12">
                                                            <label for="f-name">Country/Region:</label>
                                                            <select class="form-select form-control"
                                                                name="billingcountry" id="f-name"
                                                                aria-label="Default select example">
                                                                <option value="1" selected>India</option>
                                                            </select>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <label for="f-name">First Name:</label>
                                                    <input type="text" name="billingfirstName" id="f-name"
                                                        class="form-control" value="" />
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <label for="f-name">Middle Name:</label>
                                                    <input type="text" name="billingmiddleName" id="f-name"
                                                        class="form-control" value="" />
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <label for="l-name">Last Name:</label>
                                                    <input type="text" name="billinglastName" id="l-name"
                                                        class="form-control" value="" />
                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <label for="address_1">Address:</label>
                                                    <input type="text" name="billingaddress" id="address_2"
                                                        class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <label for="address_2">Apartment/Suite/Building
                                                        (Optional)</label>
                                                    <input type="text" name="billingappartment" id="address_2"
                                                        class="form-control" value="">
                                                </div>
                                            </div>
                                        </div> 


                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <label for="landmark">Landmark:</label>
                                                    <input type="text" name="billinglandmark" id="landmark"
                                                        class="form-control" value="" />
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <label for="city-name">City:</label>
                                                    <input type="text" name="billingcity" id="city-name"
                                                        class="form-control" value="" />
                                                </div>
                                                <div class="col-lg-8 col-md-6 col-12">
                                                    <label for="state-name">State:</label>
                                                    <select class="form-select form-control" name="billingstate"
                                                        id="state-name" aria-label="Default select example">
                                                        <option selected disabled>Choose State</option>
                                                        @foreach ($states as $state)
                                                        <option value="{{ $state->id }}">
                                                            {{ $state->stateName }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <label for="postal-code">Zip / Postal Code:</label>
                                                    <input type="text" name="billingzipcode" id="postal-code"
                                                        class="form-control" value="" />
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-12">
                                                            <label for="email_2">Email Address:</label>
                                                            <input type="email" name="billingamount" id="email_2"
                                                                class="form-control" value="" />
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-12">
                                                            <label for="p-no">Phone Number:</label>
                                                            <input type="tel" maxlength="10"
                                                                name="billingphoneNumber" id="p-no"
                                                                class="form-control" value="" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="main-btn mt-3">
                                        <button type="submit" class="as_btn"><span>Continue</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--billing area end-->

                    <!--payment area start-->
                    <div class="payment-area">
                        <div class="panel-info">
                            <div class="panel-heading">Payment</div>
                        </div>
                        <div class="inline-shipping">
                            <div class="radio-check-box">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="orderType" id="razorpay_secure" value="1" checked>
                                    <label class="form-check-label" for="razorpay_secure">
                                        Razorpay Secure (UPI, Cards, Wallets, NetBanking)
                                    </label>
                                </div>
                                <div class="code-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="orderType" id="cash_on_delivery" value="2">
                                        <label class="form-check-label" for="cash_on_delivery">
                                            <strong>Cash on Delivery(COD)</strong>
                                        </label>
                                    </div>
                                    <p class="mb-0">After clicking “Pay now”, you will be redirected to Razorpay Secure (UPI, Cards,
                                        Wallets,
                                        NetBanking) to complete your purchase securely.</p>
                                </div>

                                <div class="mt-3">
                                    <div class="panel-info">
                                        <div class="panel-heading">Terms and Conditions</div>
                                    </div>
                                    <div class="check-box-conditions mt-2">
                                        <input type="checkbox" id="term_condition" name="term-condition"
                                            value="">
                                        <label for="term_condition">By using this service, you agree to our Terms and
                                            Conditions.</label>
                                    </div>
                                </div>
                                <div class="main-btn mt-3">
                                    <button type="submit" class="as_btn"
                                        id="submitOrder"><span>Continue</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--payment area end-->
                </div>
            </div>

            <div class="col-lg-5 col-md-5 col-12">
                <!--Order Summary start-->
                <div class="panel panel-info mt-0">
                    <div class="panel-heading">
                        Order Summary
                    </div>
                    <div class="panel-body review_order">
                        @foreach ($cartItems as $cartItem)
                        <div class="form-group" id="cartItem-{{ $cartItem->id }}">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-4">
                                    @php($productimage = App\Models\Product::where('id', $cartItem->product_id)->first())
                                    <img src="{{ asset($productimage->image1) }}"
                                        alt="{{ $productimage->productName }}">
                                    <div class="quantity-option">
                                        @if ($cartItem->productdetails->categoryId == 1)
                                        <div class="quantity-select mt-2">
                                            <select name="quantityDd" id="quantity-{{ $cartItem->id }}"
                                                class="form-select form-control"
                                                onchange="return changeQuantity({{ $cartItem->id }},'1','1')"
                                                required>
                                                <option value=" " selected disabled>
                                                    --Select(Carat/Ratti)--</option>
                                                @for ($i = $cartItem->productdetails->min_product_qty; $i <= $cartItem->productdetails->max_product_qty; $i += 0.5)
                                                    <option value="{{ $i }}"
                                                        {{ $i == $cartItem->quantity ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                    @endfor
                                            </select>
                                        </div>
                                        @else
                                        <span class="minus"
                                            onclick="return changeQuantity({{ $cartItem->id }},'1')"><i
                                                class="fa-regular fa-minus"></i></span>
                                        <input type="number" id="quantity-{{ $cartItem->id }}"
                                            value="{{ $cartItem->quantity }}" min="1" name="quantity"
                                            readonly>
                                        <span class="plus"
                                            onclick="return changeQuantity({{ $cartItem->id }},'2')"><i
                                                class="fa-regular fa-plus"></i></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-5">
                                    <h4>{{ $cartItem->productDetails->productName }}</h4>
                                    <h6 class="price-box">₹{{ $cartItem->productDetails->priceB2C }}
                                        <strike>₹{{ $cartItem->productDetails->priceMRP }}</strike>
                                    </h6>
                                    <h6>
                                        Delivery : ₹
                                        <span id="itemDelivery-{{ $cartItem->id }}">
                                            {{ $cartItem->deliveryPrice }}
                                        </span>
                                    </h6>
                                    <h6>
                                        Total : ₹
                                        <span id="itemTotal-{{ $cartItem->id }}">
                                            {{ $cartItem->totalPrice }}
                                        </span>
                                    </h6>

                                    <div class="extra-feature">
                                        <div class="extra-feature-list">
                                            @if ($cartItem->productDetails->activation && $cartItem->productDetails->activation->id == 2)
                                            @elseif($cartItem->productDetails->activation)
                                            <input type="checkbox" id="activation"
                                                name="is_act-{{ $cartItem->id }}"
                                                {{ $cartItem->is_act_selected ? 'checked' : '' }}
                                                value="1"
                                                onchange="return onSetChange({{ $cartItem->id }}, `{{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 0 }}`, `{{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 0 }}`)">
                                            <label for="activation"> Activation</label>
                                            @endif

                                        </div>
                                        <div class="extra-feature-list">
                                            @if ($cartItem->productDetails->certification && $cartItem->productDetails->certification->id == 2)
                                            @elseif($cartItem->productDetails->certification)
                                            <input type="checkbox" id="certificate"
                                                name="is_cert-{{ $cartItem->id }}"
                                                {{ $cartItem->is_cert_selected ? 'checked' : '' }}
                                                value="1"
                                                onchange="return onSetChange({{ $cartItem->id }}, `{{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 0 }}`, `{{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 0 }}`)">
                                            <label for="certificate"> Certificate</label>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-3 position-relative">
                                    <a onclick="return removeFromCart({{ $cartItem->id }})" href="javascript:;"
                                        class="delete-area"><i class="fa-light fa-trash-can-xmark"
                                            title="Delete"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-md-8 col-12">
                                    <input type="text" name="couponCode" id="couponCode" class="form-control"
                                        placeholder="Discount Code" value="" />
                                </div>
                                <div class="col-lg-5 col-md-6 col-12">
                                    <div class="main-btn mt-0">
                                        <button onclick="applyCoupon()" type="button"
                                            class="as_btn couponApplyBtn"><span>Apply</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="total_order">
                            <h4>Subtotal</h4>
                            <h6>₹<span id="total"> {{ $subtotal }}</span></h6>

                        </div>
                        <div class="total_order dvalue d-none">
                            <h4>Discounted value</h4>
                            <h6>₹<span id="discountedtotal"></span></h6>

                        </div>
                        <div class="total_order">
                            <h4>Shipping</h4>
                            <h6>₹ <span id="deliveryCharges">{{ $totalDelPrice }}</span></h6>
                        </div>
                        <div class="total_order">
                            <h4>Total</h4>
                            <h6>₹<span class="subTotal"> {{ $total }}</span></h6>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between main-btn align-items-center">
                    <button type="submit" class="as_btn" id="submitOrder"><span>Continue</span></button>
                    <a href="/cart" style="color: #ee2761"><i class="fa-light fa-rotate-left"></i> Return to
                        cart</a>
                </div>
                <!--Order Summary end-->
            </div>

        </form>
    </div>
</div>
<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content"
            style="background: linear-gradient(135deg, #f0f8ff, #e6e6fa); border-radius: 20px;">
            <form id="editAddressForm">
                @csrf
                @method('PUT')
                <div class="modal-header"
                    style="background-color: #6c63ff; color: white; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                    <input type="hidden" id="addressId">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control shadow-sm" id="first_name" name="first_name"
                                placeholder="Enter first name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control shadow-sm" id="middle_name" name="middle_name"
                                placeholder="Enter middle name">
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control shadow-sm" id="last_name" name="last_name"
                                placeholder="Enter last name" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control shadow-sm" id="phone" name="phone"
                                placeholder="Enter phone number">
                        </div>
                        <div class="col-md-6">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control shadow-sm" id="zip_code" name="zip_code"
                                placeholder="Enter zip code">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select shadow-sm" id="country" name="country_id">
                                <option value="">Select Country</option>
                                <option value="1">India</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select shadow-sm" id="state" name="state_id">
                                <option value="">Select State</option>
                                @foreach ($states as $statesdata)
                                <option value="{{ $statesdata->id }}">{{ $statesdata->stateName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control shadow-sm" id="city" name="city_name"
                                placeholder="Enter city">
                        </div>
                        <div class="col-md-6">
                            <label for="landmark" class="form-label">Landmark</label>
                            <input type="text" class="form-control shadow-sm" id="landmark" name="landmark"
                                placeholder="Enter landmark">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="apartment" class="form-label">Apartment</label>
                            <input type="text" class="form-control shadow-sm" id="apartment" name="apartment"
                                placeholder="Enter apartment">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Type</label>
                            <div>
                                <input type="radio" class="form-check-input" id="address_type_home"
                                    name="address_type" value="Home">
                                <label for="address_type_home" class="form-check-label me-3">Home</label>
                                <input type="radio" class="form-check-input" id="address_type_office"
                                    name="address_type" value="Work">
                                <label for="address_type_work" class="form-check-label me-3">Work</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control shadow-sm" id="address" name="address" rows="3" placeholder="Enter address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"
                    style="background-color: #f0f8ff; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script src="{{ url('/') }}/user/assets/js/jquery.js"></script>
<link rel="stylesheet" href="{{ url('/') }}/user/assets/css/bootstrap.css">
<script src="{{ url('/') }}/user/assets/js/bootstrap.js"></script>
<script src="{{ url('/') }}/assets/js/checkout-validation.js"></script>
@if (Auth::guard('euser')->check())
<meta name="auth-check" content="true">
@endif
<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-otp shadow-lg rounded-4">
            <div class="modal-header modal-header-otp bg-primary text-white">
                <h5 class="modal-title" id="otpModalLabel">🔐 Verify Your Mobile Number</h5>
                {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button> --}}
            </div>
            <div class="modal-body p-4">
                <form id="otpForm">
                    @csrf
                    <div class="form-group">
                        <label for="mobile" class="fw-bold">📱 Mobile Number</label>
                        <input type="text"
                            class="form-control form-control-otp rounded-pill bg-light text-dark fw-semibold"
                            id="mobile" name="mobile" placeholder="Enter your mobile number" required>
                        <span id="mobileError" class="text-danger small"></span>
                    </div>
                    <div class="form-group mt-3 text-center">
                        <button type="button" class="btn btn-otp btn-primary px-4 py-2 rounded-pill shadow-sm"
                            id="sendOtp">
                            📩 Send OTP
                        </button>
                        <span id="timer" class="text-danger fw-bold" style="display: none;"></span>
                        <button type="button" class="btn btn-otp btn-secondary px-4 py-2 rounded-pill shadow-sm"
                            id="resendOtp" style="display: none;">
                            🔄 Resend OTP
                        </button>
                    </div>

                    <div class="form-group mt-4" id="otpField" style="display: none;">
                        <label for="otp" class="fw-bold">🔑 Enter OTP</label>
                        <input type="text"
                            class="form-control form-control-otp rounded-pill bg-light text-dark fw-semibold"
                            id="otp" name="otp" placeholder="Enter OTP" required>
                        <span id="otpError" class="text-danger small"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                {{-- <button type="button" class="btn btn-otp btn-secondary rounded-pill px-4 py-2"
                    data-bs-dismiss="modal">❌
                    Close</button> --}}
                <button type="button" class="btn btn-otp btn-success rounded-pill px-4 py-2 shadow-sm"
                    id="verifyOtp" style="display: none;">
                    ✅ Verify OTP
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const updateTotalWithCod = () => {
    let isCOD = $('#cash_on_delivery').is(':checked');
    let subtotal = parseFloat($('#total').text()); // Get the updated subtotal from response
    let deliveryCharges = parseFloat($('#deliveryCharges').text());
    let codCharge = 30;
    let totalAmount = subtotal + deliveryCharges;

    if (isCOD) {
        totalAmount += codCharge;
        if (!$('.codCharge').length) {
            $('.total_order:last').before(`
                <div class="total_order codCharge">
                    <h4>COD Charges applied</h4>
                    <h6>₹30</h6>
                </div>
            `);
        }
    } else {
        $('.codCharge').remove();
    }

    $.ajax({
        type: 'POST',
        url: '/set-cod-session', // Create this route/controller
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            isCOD: isCOD
        },
        success: function () {
            applyCoupon();
        }
    });

    $('.subTotal').text(totalAmount);
    $('.amount').val(totalAmount);
};

    const removeFromCart = (cartItemId) => {

        // var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
        if (confirm('Are you sure you want to remove this item?')) {
            $.ajax({
                type: "POST",
                url: "{{ route('removeFromCart') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    cartItemId: cartItemId,
                },
                success: function(response) {
                    alert(response.message);
                    $(".cartCount").text(response.totalCartItems);
                    $('#cartItem-' + cartItemId).remove();
                    $('.subTotal').text(`${response.subtotal}`);
                    $('.amount').val(response.subtotal);
                    $('#deliveryCharges').text(response.totalDelPrice);
                    $('#total').text(response.total);

                    updateTotalWithCod();

                    // toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    // toastr.error("An error occurred: " + error); 
                },
            });
        }

    };
    const changeQuantity = (id, operation, selectedQuantity) => {
        let couponApplied = {{ session('coupon') ? 'true' : 'false' }};

        var quantity = parseFloat($(`input[id="quantity-${id}"], select[id="quantity-${id}"]`).val());
        var newQuantity = operation == 2 ? quantity + 1 : quantity - 1;

        if (newQuantity < 1) {
            newQuantity = 1;
            return false;
        }

        if (selectedQuantity)
            newQuantity = $(`select[id="quantity-${id}"]`).val();
        $.ajax({
            type: "POST",
            url: "{{ route('changeQuantity') }}",
            data: {
                _token: "{{ csrf_token() }}",
                cartItemId: id,
                quantity: newQuantity,
            },
            success: function(response) {
                // $(".cartCount").text(response.totalCartItems);
                $('.subTotal').text(`${response.subtotal}`);
                $('#total').text(response.total);
                $('.amount').val(response.subtotal);
                $('#itemTotal-' + id).text(response.itemTotal);
                $('#deliveryCharges').text(response.totalDelPrice);
                $('#itemDelivery-' + id).text(response.itemDeliveryPrice);

                updateTotalWithCod();

                if (couponApplied === true || couponApplied === 'true') {
                // Make an AJAX request to remove the coupon session
                $.ajax({
                    type: "POST",
                    url: "{{ route('destroyCoupon') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(removeResponse) {
                        location.reload(); // Refresh the page after coupon is removed
                    },
                    error: function(xhr, status, error) {
                        alert("Error removing coupon: " + error);
                    }
                });
            }

                // $('#quantity-' + id).text(newQuantity);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    }
    const onSetChange = (id, act, cert) => {
        var isActive = $(`input[name="is_act-${id}"]`).is(':checked') ? $(`input[name="is_act-${id}"]`).val() : 0;
        var isCert = $(`input[name="is_cert-${id}"]`).is(':checked') ? $(`input[name="is_cert-${id}"]`).val() : 0;
        if (act == "Free" || act == "N/A")
            act = 0;
        if (cert == "Free" || cert == "N/A")
            cert = 0;
        $.ajax({
            type: "POST",
            url: "{{ route('changeAddSettings') }}",
            data: {
                _token: "{{ csrf_token() }}",
                cartItemId: id,
                activation: act,
                certificate: cert,
                isActive: isActive,
                isCert: isCert,
            },
            success: function(response) {
                // $(".cartCount").text(response.totalCartItems);
                alert(response.message);
                $('.subTotal').text(`${response.subtotal}`);
                $('.amount').val(response.subtotal);
                $('#itemTotal-' + id).text(response.itemTotal);
                $('#itemDelivery-' + id).text(response.itemDeliveryPrice);
                $('#deliveryCharges').text(response.totalDelPrice);
                $('#total').text(response.total);
                // $('#quantity-' + id).text(newQuantity);
                // toastr.success(response.message);
                updateTotalWithCod();
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });

    }

    $(document).ready(function () {
        $('input[name="orderType"]').change(updateTotalWithCod);
    });

    function applyCoupon() {
        const couponCode = $('input[name="couponCode"]').val(); // Get the coupon code from the input
        const cartId = "{{ $cartId }}"; // Get the cart ID
        const isCOD = $('#cash_on_delivery').is(':checked');

        $.ajax({
            type: "POST",
            url: "{{ route('coupon.applyCoupon') }}", // Replace with your route for applying the coupon
            data: {
                _token: "{{ csrf_token() }}", // CSRF token for security
                couponName: couponCode, // Send the coupon code
                cartId: cartId, // Send the cart ID
                isCOD: isCOD ? 1 : 0
            },
            success: function(response) {
                if (response.discount === 0) {
                toastr.error('Coupon not found'); // Show error message if discount is 0
                 } else {
                // Assuming response contains subtotal, discount, and finalAmount
                toastr.success(`Discount of Rs ${response.discount} applied!`); // Show a success message
                $('.amount').val(response.finalAmount);
                $('.subTotal').text(response.finalAmount);
                $('#discountedtotal').text(response.finalSubtotal);
                $('.dvalue').removeClass('d-none');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'appliedCoupon',
                    value: couponCode
                }).appendTo('form');
                $('#couponCode').prop('disabled', true);
                $('.couponApplyBtn').prop('disabled', true);
                // $('.subTotal').text(`${response.subtotal}`); // Update the subtotal on the page
                // $('#total').text(`${response.finalAmount}`); // Update the total amount on the page
                }
            },
            error: function(xhr, status, error) {
                alert(JSON.parse(xhr.responseText)
                    .message); // You can customize this message
            },
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addressRadios = document.querySelectorAll('input[name="address-option-radio"]');
        const formContainer = document.querySelector('.new-address');
        const saveaddressContainer = document.querySelector('.save-address');

        addressRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    formContainer.style.display = 'block';
                    saveaddressContainer.style.display = 'none';

                    // Populate the form fields
                    document.querySelector('input[name="firstName"]').value = radio.dataset
                        .first_name || '';
                    document.querySelector('input[name="middleName"]').value = radio.dataset
                        .middle_name || '';
                    document.querySelector('input[name="lastName"]').value = radio.dataset
                        .last_name || '';
                    document.querySelector('input[name="phoneNumber"]').value = radio.dataset
                        .phone || '';
                    document.querySelector('input[name="address"]').value = radio.dataset
                        .address || '';
                    document.querySelector('input[name="appartment"]').value = radio.dataset
                        .apartment || '';
                    document.querySelector('input[name="landmark"]').value = radio.dataset
                        .landmark || '';
                    document.querySelector('input[name="city"]').value = radio.dataset.city ||
                        '';
                    document.querySelector('select[name="state"]').value = radio.dataset
                        .state_id || '';
                    document.querySelector('input[name="zipcode"]').value = radio.dataset
                        .zipcode || '';
                    document.querySelector('input[name="address_type"][value="1"]').checked =
                        radio.dataset.address_type === 'Home';
                    document.querySelector('input[name="address_type"][value="2"]').checked =
                        radio.dataset.address_type === 'Work';
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Open Modal and Fetch Data
        $('.edit-btn').on('click', function() {
            let addressId = $(this).data('id'); // Ensure data-id exists and is correct
            let fetchUrl = "{{ route('euser.address.getaddress', ':id') }}".replace(':id', addressId);

            $.ajax({
                url: fetchUrl,
                method: 'GET',
                success: function(data) {
                    console.log('Received data:', data);

                    $('#addressId').val(data.id);
                    $('#first_name').val(data.first_name || '');
                    $('#middle_name').val(data.middle_name || '');
                    $('#last_name').val(data.last_name || '');
                    $('#phone').val(data.phone || '');
                    $('#zip_code').val(data.zip_code || '');
                    $('#landmark').val(data.landmark || '');
                    $('#apartment').val(data.apartment || '');
                    $('#city').val(data.city_name || '');
                    $('#address').val(data.address || '');

                    $('#country').val(data.country_id || '')
                        .change();

                    $('#state').val(data.state_id || '')
                        .change();

                    $(`input[name="address_type"][value="${data.address_type || ''}"]`)
                        .prop('checked', true);

                    $('#editAddressModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error fetching data:', xhr
                        .responseText);
                    toastr.error('Failed to fetch address data. Please try again.');
                },
            });
        });



        $('#editAddressForm').on('submit', function(e) {
            e.preventDefault();
            let addressId = $('#addressId').val();
            let updateUrl = "{{ route('euser.address.update', ':id') }}".replace(':id', addressId);

            $.ajax({
                url: updateUrl,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success(response.success || 'Address updated successfully.');
                    $('#editAddressModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        toastr.error(errors[key][0]);
                    }
                },
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".add-new-address").click(function() {
            let newAddressForm = $(".new-address");
            let saveAddressCheckbox = $(".save-address");

            if (newAddressForm.is(":visible")) {
                let form = newAddressForm;

                form.find("input[type='text'], input[type='number'], input[type='tel']").val("");

                form.find("input[type='radio']").prop("checked", false);
                form.find("input[name='address_type'][value='1']").prop("checked",
                    true);

                $(".login-option input[type='radio']").prop("checked", false);

                form.find("input[type='checkbox']").prop("checked", false);

                form.find("select").prop("selectedIndex", 0);
                form.find("select[name='country']").val("1");

                form.find("input[name='firstName']").val("");
                form.find("input[name='middleName']").val("");
                form.find("input[name='lastName']").val("");
                form.find("input[name='address']").val("");
                form.find("input[name='appartment']").val("");
                form.find("input[name='landmark']").val("");
                form.find("input[name='city']").val("");
                form.find("select[name='state']").prop("selectedIndex", 0);
                form.find("input[name='zipcode']").val("");
                form.find("input[name='phoneNumber']").val("");

                saveAddressCheckbox.show();
            } else {
                newAddressForm.show();

                saveAddressCheckbox.show();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#otpModal').on('show.bs.modal', function() {
            let phoneNumber = $('#phoneNumber').val();
            $('#mobile').val(phoneNumber);
        });
    });
</script>
<script>
    $(document).ready(function() {
        function startTimer(duration) {
            let timer = duration,
                seconds;
            let countdown = setInterval(function() {
                seconds = timer;
                $('#timer').text(`Resend OTP in ${seconds}s`).show();

                if (--timer < 0) {
                    clearInterval(countdown);
                    $('#timer').hide();
                    $('#resendOtp').show(); // Show Resend OTP button after timer ends
                }
            }, 1000);
        }

        $("#sendOtp, #resendOtp").click(function() {
            const mobile = $('#mobile').val();
            if (!mobile) {
                $('#mobileError').text('Mobile number is required.');
                return;
            }

            let buttonId = $(this).attr('id');
            $('#' + buttonId).prop('disabled', true).text('Processing...');

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            fetch('/user/checkout-send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        mobile
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    $('#' + buttonId).prop('disabled', false).text(buttonId === "sendOtp" ?
                        'Send OTP' : 'Resend OTP');
                    if (data.success) {
                        $('#otpField').show();
                        $('#verifyOtp').show();
                        $('#mobileError').text('');

                        // Hide both buttons, show timer, start countdown
                        $('#sendOtp, #resendOtp').hide();
                        startTimer(30);
                    } else {
                        $('#mobileError').text(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#' + buttonId).prop('disabled', false).text(buttonId === "sendOtp" ?
                        'Send OTP' : 'Resend OTP');
                });
        });
        $("#verifyOtp").click(function() {
            const mobile = $('#mobile').val();
            const otp = $('#otp').val();
            if (!otp) {
                $('#otpError').text('OTP is required.');
                return;
            }

            $('#verifyOtp').prop('disabled', true).text('Verifying...');

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": 1000,
                "extendedTimeOut": 1000,
                "hideDuration": 1000,
                "showDuration": 1000
            };

            fetch('/user/checkout-verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        mobile,
                        otp
                    }),
                })
                .then(response => response.json())
                .then(data => { 
                    debugger
                    $('#verifyOtp').prop('disabled', false).text('Verify OTP');
                    if (data.success) {
                        toastr.success(data.message);
                        window.location.href = data.redirect_url;
                    } else {
                        $('#otpError').text(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#verifyOtp').prop('disabled', false).text('Verify OTP');
                });
        });
    });
</script>
<style>
    .modal-body .form-control {
        background-color: #ff8484 !important;
        color: #040404 !important;
    }

    .modal-content .modal-content-otp {
        border: none;
    }

    .modal-header .modal-header-otp {
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }

    .form-control .form-control-otp {
        border: 2px solid #ccc;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .btn .btn-otp {
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>
