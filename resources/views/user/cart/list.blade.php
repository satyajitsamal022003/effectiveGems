@extends('user.layout')
@section('content')
    <section class="container">
        <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-1.jpg');">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Our Cart</h1>
                    <ul class="breadcrumb">
                        <li><a href="{{ route('user.index') }}">Home</a></li>
                        <li>Cart</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding cart-page as_padderBottom40 as_padderTop40">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12">
                    <table class="rwd-table">
                        <tbody>
                            <tr>
                                <th>IMAGE</th>
                                <th>PRODUCT NAME</th>
                                <th>QUANTITY</th>
                                <th>PRICE</th>
                                <th>DELETE</th>
                                <th>Total</th>
                                <th>ACTION</th>
                            </tr>
                            @foreach ($cartItems as $cartItem)
                                <tr id="cartItem-{{ $cartItem->id }}">
                                    <td data-th="IMAGE">
                                        @php($productimage = App\Models\Product::where('id', $cartItem->product_id)->first())
                                        <img src="{{ asset($productimage->image1) }}" alt="{{ $productimage->productName }}"
                                            class="pro-img">
                                    </td>
                                    <td data-th="PRODUCT NAME">
                                        <div class="cart-responsive">
                                            <h4>{{ $cartItem->productDetails->productName }}</h4>
                                            <p class="delivery-charges">
                                                Delivery Charges Apply <i class="fa-solid fa-indian-rupee-sign"></i>
                                                <span id="courierPrice-{{ $cartItem->id }}">
                                                    @if ($cartItem->productDetails->courierTypeId != 1 && $cartItem->productDetails->courierTypeId != 2)
                                                        {{ $cartItem->productDetails->courierType->courier_price * $cartItem->quantity }}
                                                    @else
                                                        {{ $cartItem->productDetails->courierType->courier_price }}
                                                    @endif
                                                </span>
                                            </p>
                                            <div class="extra-feature">
                                                <div class="extra-feature-list">
                                                    @if ($cartItem->productDetails->activation && $cartItem->productDetails->activation->id == 1)
                                                        Activation: (Free)
                                                    @elseif($cartItem->productDetails->activation && $cartItem->productDetails->activation->id == 2)

                                                    @elseif($cartItem->productDetails->activation)
                                                        <input type="checkbox" id="activation" value="1" name="is_act"
                                                            {{ $cartItem->is_act_selected == 1 ? 'checked' : '' }}
                                                            onchange="return onSetChange({{ $cartItem->id }}, `{{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 0 }}`, `{{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 0 }}`)">
                                                        <label for="activation"> Activation
                                                            ({{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 'Free' }})
                                                        </label>
                                                    @else
                                                    @endif

                                                </div>
                                                <div class="extra-feature-list">
                                                    @if ($cartItem->productDetails->certification && $cartItem->productDetails->certification->id == 1)
                                                        Certification: (Free)
                                                    @elseif($cartItem->productDetails->certification && $cartItem->productDetails->certification->id == 2)

                                                    @elseif($cartItem->productDetails->certification)
                                                        <input type="checkbox" id="certificate" name="is_cert"
                                                            value="1"
                                                            {{ $cartItem->is_cert_selected == 1 ? 'checked' : '' }}
                                                            onchange="return onSetChange({{ $cartItem->id }}, `{{ $cartItem->productDetails->activation ? $cartItem->productDetails->activation->amount : 0 }}`, `{{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 0 }}`)">
                                                        <label for="certificate"> Certificate
                                                            ({{ $cartItem->productDetails->certification ? $cartItem->productDetails->certification->amount : 'Free' }})
                                                        </label>
                                                    @else
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-th="QUANTITY">
                                        <div class="quantity">
                                            @if ($cartItem->productdetails->categoryId == 1)
                                                <div class="quantity-select mt-2">
                                                    <select name="quantityDd" id="quantityDd" class="form-select"
                                                        onchange="return changeQuantity({{ $cartItem->id }},'1','1')">
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
                                    </td>
                                    <td data-th="PRICE">
                                        <div class="cart-price-section">
                                            ₹{{ $cartItem->productDetails->priceB2C }}
                                            <strike>₹{{ $cartItem->productDetails->priceMRP }}</strike>
                                        </div>
                                    </td>
                                    <td data-th="DELETE">
                                        <a onclick="return removeFromCart({{ $cartItem->id }})"> <i
                                                class="fa-light fa-trash-can" style="color: red;cursor: pointer;"
                                                title="Delete"></i></a>
                                    </td>
                                    <td>
                                        <div class="cart-price-section">
                                            ₹<span id="itemTotal-{{ $cartItem->id }}">{{ $cartItem->totalPrice }}</span>
                                        </div>

                                    </td>
                                    <td data-th="ACTION">
                                        <div class="main-btn mt-0">
                                            <button type="button" class="as_btn"><span>Move to Wishlist</span></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="cart-bg-right">
                        <div class="cart-total">
                            <h3>Sub Total : Rs. <span class="subTotal"> {{ $subtotal }}</span></h3>
                            <p>Tax included. <a href="#"><u>Shipping</u></a> calculated at checkout.</p>
                            <div class="extra-feature-list">
                                <input required type="checkbox" id="agree-cart" name="agree-cart" value="1">
                                <label for="agree-cart"> I agree with the <a href="#" class="text-black"><u>terms
                                            and
                                            conditions</u></a></label>
                            </div>
                        </div>
                        <div class="cart-inline-btn main-btn text-end mt-3">
                            <a href="/checkout" class="as_btn">Checkout</a>
                        </div>
                        <div class="safe-checkout">
                            <h5>Guarantee Safe Checkout</h5>
                            <img src="{{url('/')}}/user/assets/images/payment-option.png" alt="payment-option">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                    $('.subTotal').text(response.subtotal);

                    // toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    // toastr.error("An error occurred: " + error);
                },
            });
        }

    };
    const changeQuantity = (id, operation, selectedQuantity) => {

        var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
        var newQuantity = operation == 2 ? quantity + 1 : quantity - 1;
        if (selectedQuantity)
            newQuantity = $('select[name="quantityDd"]').val();

        // Calculate the courier price
        var courierPrice = 0;
        var productPrice = {{ $cartItem->productDetails->courierType->courier_price }};
        console.log(productPrice);
        

        if ({{ $cartItem->productDetails->courierTypeId }} != 1 &&
            {{ $cartItem->productDetails->courierTypeId }} != 2) {
            courierPrice = productPrice * newQuantity; // Update courier price based on quantity
        } else {
            courierPrice = productPrice; // Default price when condition doesn't match
        }

        // Update the courier price display
        // Assuming you have an element to show the courier price, e.g., a span with a class `courierPrice`
        $(`#courierPrice-${id}`).text(courierPrice);

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
                // alert(response.message);

                $('.subTotal').text(response.subtotal);

                $('#itemTotal-' + id).text(response.itemTotal);
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
                $('.subTotal').text(response.subtotal);
                $('#itemTotal-' + id).text(response.itemTotal);
                // $('#quantity-' + id).text(newQuantity);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });

    }
</script>
