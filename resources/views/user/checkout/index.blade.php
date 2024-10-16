@extends('user.layout')
@section('content')
    <div class="container checkout_style as_padderBottom40">
        <div class="cart-body section-padding">

            <form method="POST" action="{{ url('/checkout') }}" id="orderForm" class="row">
                @csrf
                <div class="col-lg-7 col-md-7 col-12">
                    <div class="checkout-main-form">
                        <input type="hidden" name="amount" class="amount" value="{{ $subtotal }}">
                        <!--customer area start-->
                        <div class="customer-email">
                            <div class="panel-info mt-0">
                                <div class="panel-heading">Contact</div>
                                <a href="login.html" class="edit-after-submit">Login</a>
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
                        <!--customer area end-->

                        <!--shipping area start-->
                        <div class="shipping-area">
                            <div class="panel-info">
                                <div class="panel-heading">Shipping Address</div>
                            </div>
                            {{-- <a href="javascript:;" class="add-new-address"><i class="fa-light fa-plus"></i> Add New Address</a> --}}
                            <!--if login then show option start-->
                            {{-- <div class="login-option mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="login-option-list">
                                            <a href="javascript:;" class="edit-btn"><i class="fa-solid fa-pen-to-square"></i>
                                                Edit</a>
                                            <label for="login-option-1">
                                                <input type="radio" id="login-option-1" name="login-option-radio"
                                                    value="">
                                                <h4>Hemanta Maharana</h4>
                                                <p><i class="fa-solid fa-phone"></i> +91 123 456 7890</p>
                                                <p><i class="fa-solid fa-envelope"></i> hemanta@gmail.com</p>
                                                <hr>
                                                <p>Near Binapani Coaching Centre Saheed Nagar Khordha - 750017, Bhubaneswar,
                                                    Odisha, India</p>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="login-option-list">
                                            <a href="javascript:;" class="edit-btn"><i class="fa-solid fa-pen-to-square"></i>
                                                Edit</a>
                                            <label for="login-option-2">
                                                <input type="radio" id="login-option-2" name="login-option-radio"
                                                    value="">
                                                <h4>Hemanta Maharana</h4>
                                                <p><i class="fa-solid fa-phone"></i> +91 123 456 7890</p>
                                                <p><i class="fa-solid fa-envelope"></i> hemanta@gmail.com</p>
                                                <hr>
                                                <p>Near Jagannath Temple - 767001, Balangir, Odisha, India</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <!--if login then show option end-->
                            <div class="new-address">
                                <div class="row justify-content-center inline-shipping">
                                    <div class="col-lg-12 col-12">
                                        <div class="shipping-section">
                                            <div method="post" action="">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <label for="f-name">First Name:</label>
                                                            <input type="text" required name="firstName" id="f-name"
                                                                class="form-control" value="" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <label for="f-name">Middle Name:</label>
                                                            <input type="text" name="middleName" id="f-name"
                                                                class="form-control" value="" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <label for="l-name">Last Name:</label>
                                                            <input type="text" required name="lastName" id="l-name"
                                                                class="form-control" value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-6 col-12">
                                                            <label for="p-no">Phone Number:</label>
                                                            <input type="number" required type="tel" maxlength="10"
                                                                name="phoneNumber" id="p-no" class="form-control"
                                                                value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-12">
                                                            <label for="f-name">Country/Region:</label>
                                                            <select name="country" required class="form-select form-control"
                                                                id="f-name" aria-label="Default select example">
                                                                <option value="1" selected>India</option>

                                                            </select>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-12">
                                                            <label for="state-name">State:</label>
                                                            <select name="state" required class="form-select form-control"
                                                                id="state-name" aria-label="Default select example">
                                                                <option selected disabled>Choose State</option>
                                                                @foreach ($states as $state)
                                                                    <option value="{{ $state->id }}">
                                                                        {{ $state->stateName }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <label for="city-name">City:</label>
                                                            <input name="city" required type="text" name=""
                                                                id="city-name" class="form-control" value="" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <label for="postal-code">Zip / Postal Code:</label>
                                                            <input name="zipcode" required type="number" name=""
                                                                id="postal-code" class="form-control" value="" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <label for="landmark">Landmark:</label>
                                                            <input name="landmark" required type="text" name=""
                                                                id="landmark" class="form-control" value="" />
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
                                                        <div class="col-lg-12 col-md-12 col-12">
                                                            <label for="address_1">Address:</label>
                                                            <input name="address" required type="text" name=""
                                                                id="address_2" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex_box">
                                                <input type="checkbox" id="billing_information" name="billing_1"
                                                    value="">
                                                <label for="billing_information">Save this information for next
                                                    time</label>
                                            </div>
                                            <div class="mt-0 flex_box">
                                                <input type="checkbox" id="billing_same_address" name="billing_1"
                                                    value="">
                                                <label for="billing_same_address">My billing address is the same as my
                                                    shipping
                                                    address.</label>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
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
                                            id="same_address" onchange="$('.billingAddress').hide()" checked >
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
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="f-name">First Name:</label>
                                                        <input type="text" name="billingfirstName" id="f-name"
                                                            class="form-control" value="" required/>
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
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="email_2">Email Address:</label>
                                                        <input type="email" name="billingamount" id="email_2"
                                                            class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="p-no">Phone Number:</label>
                                                        <input type="tel" maxlength="10" name="billingphoneNumber"
                                                            id="p-no" class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="f-name">Country/Region:</label>
                                                        <select class="form-select form-control" name="billingcountry"
                                                            id="f-name" aria-label="Default select example">
                                                            <option selected disabled>Choose Country</option>
                                                            <option value="1">India</option>
                                                            <option value="2">United States</option>
                                                            <option value="3">Netherlands</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="state-name">State:</label>
                                                        <select class="form-select form-control" name="billingstate"
                                                            id="state-name" aria-label="Default select example">
                                                            <option selected disabled>Choose State</option>
                                                            <option value="1">Odisha</option>
                                                            <option value="2">Andhra Pradesh</option>
                                                            <option value="3">Delhi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="city-name">City:</label>
                                                        <input type="text" name="billingcity" id="city-name"
                                                            class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="postal-code">Zip / Postal Code:</label>
                                                        <input type="text" name="billingzipcode" id="postal-code"
                                                            class="form-control" value="" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <label for="landmark">Landmark:</label>
                                                        <input type="text" name="billinglandmark" id="landmark"
                                                            class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <label for="address_2">Apartment/Suite/Building (Optional)</label>
                                                        <input type="text" name="billingappartment" id="address_2"
                                                            class="form-control" value="">
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
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="razorpay_secure" checked>
                                        <label class="form-check-label" for="razorpay_secure">
                                            Razorpay Secure (UPI, Cards, Wallets, NetBanking)
                                        </label>
                                    </div>
                                    <p>After clicking “Pay now”, you will be redirected to Razorpay Secure (UPI, Cards,
                                        Wallets,
                                        NetBanking) to complete your purchase securely.</p>

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
                                        <button type="submit" class="as_btn"><span>Continue</span></button>
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
                                                    @if ($cartItem->productDetails->activation && $cartItem->productDetails->activation->id == 1)
                                                        Activation: (Free)
                                                    @elseif($cartItem->productDetails->activation && $cartItem->productDetails->activation->id == 2)

                                                    @elseif($cartItem->productDetails->activation)
                                                        ({{ $cartItem->productDetails->activation->amount ?? 'N/A' }})
                                                        <input type="checkbox" id="activation" name="is_act"
                                                            {{ $cartItem->is_act_selected ? 'checked' : '' }}
                                                            value="1"
                                                            onchange="return onSetChange({{ $cartItem->id }}, `{{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 0 }}`, `{{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 0 }}`)">
                                                        <label for="activation"> Activation</label>
                                                    @else
                                                    @endif

                                                </div>
                                                <div class="extra-feature-list">
                                                    @if ($cartItem->productDetails->certification && $cartItem->productDetails->certification->id == 1)
                                                        Certification: (Free)
                                                    @elseif($cartItem->productDetails->certification && $cartItem->productDetails->certification->id == 2)

                                                    @elseif($cartItem->productDetails->certification)
                                                        ({{ $cartItem->productDetails->certification->amount ?? 'N/A' }})
                                                        <input type="checkbox" id="certificate" name="is_cert"
                                                            {{ $cartItem->is_cert_selected ? 'checked' : '' }}
                                                            value="1"
                                                            onchange="return onSetChange({{ $cartItem->id }}, `{{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 0 }}`, `{{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 0 }}`)">
                                                        <label for="certificate"> Certificate</label>
                                                    @else
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
                                        <input type="text" name="" class="form-control"
                                            placeholder="Discount Code" value="" />
                                    </div>
                                    <div class="col-lg-5 col-md-6 col-12">
                                        <div class="main-btn mt-0">
                                            <button type="button" class="as_btn"><span>Apply</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="total_order">
                                <h4>Subtotal</h4>
                                <h6>₹<span id="total"> {{ $total }}</span></h6>

                            </div>
                            <div class="total_order">
                                <h4>Shipping</h4>
                                <h6>₹ <span id="deliveryCharges">{{ $totalDelPrice }}</span></h6>
                            </div>
                            <div class="total_order">
                                <h4>Total</h4>
                                <h6>₹<span class="subTotal"> {{ $subtotal }}</span></h6>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between main-btn align-items-center">
                        <button type="submit" class="as_btn"><span>Continue</span></button>
                        <a href="/cart" style="color: #ee2761"><i class="fa-light fa-rotate-left"></i> Return to
                            cart</a>
                    </div>
                    <!--Order Summary end-->
                </div>

            </form>
        </div>
    </div>
@endsection
<script>
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
                    // toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    // toastr.error("An error occurred: " + error);
                },
            });
        }

    };
    const changeQuantity = (id, operation, selectedQuantity) => {
        var quantity = parseFloat($(`input[id="quantity-${id}"], select[id="quantity-${id}"]`).val());
        var newQuantity = operation == 2 ? quantity + 1 : quantity - 1;
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

                // $('#quantity-' + id).text(newQuantity);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    }
    const onSetChange = (id, act, cert) => {
        var isActive = $('input[name="is_act"]').is(':checked') ? $('input[name="is_act"]').val() : 0;
        var isCert = $('input[name="is_cert"]').is(':checked') ? $('input[name="is_cert"]').val() : 0;
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
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });

    }
</script>
