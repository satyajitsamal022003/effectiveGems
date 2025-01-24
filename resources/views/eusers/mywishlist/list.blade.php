@extends('user.layout')
@section('content')
    @include('eusers.partials.header')
    <section class="container mb-5">
        <div class="account-body">
            <div class="cart-page">
                <table class="rwd-table">
                    <tbody>
                        <tr>
                            <th>DELETE</th>
                            <th>PRODUCT IMAGE</th>
                            <th>PRODUCT NAME</th>
                            <th>STOCK STATUS</th>
                            <th>PRICE</th>
                            <th>ACTION</th>
                        </tr>
                        @foreach ($wishlists as $w)
                            <tr>
                                <td data-th="DELETE">
                                    <a href="#"
                                        onclick="return remove({{ $w->id }})">
                                        <i class="fa-light fa-trash-can"
                                            style="color: red;cursor: pointer;" title="Delete"></i></a>
                                </td>
                                <td data-th="PRODUCT IMAGE">
                                    <img src="{{ asset($w->productDetails->image1 ?? 'defaultImage.jpeg') }}" alt="image"
                                        class="pro-img">
                                </td>
                                <td data-th="PRODUCT NAME">
                                    <a href="../product-details.html">{{ $w->productDetails->productName }}</a>
                                </td>
                                <td data-th="Stock Check">
                                    In Stock
                                </td>
                                <td data-th="PRODUCT PRICE">
                                    ₹{{ $w->productDetails->priceB2C }}
                                </td>
                                <td data-th="Action" class="main-btn">
                                    <a href="#" CLASS="as_btn"
                                        onclick="return addToCart({{ $w->productdetails->id }},{{ $w->productdetails->min_product_qty }})">Add
                                        to Cart</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

<script>
    const addToCart = (proId, quantity) => {

        // var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
        // var isActive = $('input[name="is_act"]').is(':checked') ? $('input[name="is_act"]').val() : 0;
        // var isCert = $('input[name="is_cert"]').is(':checked') ? $('input[name="is_cert"]').val() : 0;
        $.ajax({
            type: "POST",
            url: "{{ route('addToCart') }}",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: proId,
                quantity: quantity,
                // isActive: isActive,
                // isCert: isCert,
            },
            success: function(response) {
                // $(".cartCount").text(response.totalCartItems);
                alert(response.message);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    };
    const remove = (proId) => {

        // var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
        // var isActive = $('input[name="is_act"]').is(':checked') ? $('input[name="is_act"]').val() : 0;
        // var isCert = $('input[name="is_cert"]').is(':checked') ? $('input[name="is_cert"]').val() : 0;
        $.ajax({
            type: "POST",
            url: "{{ route('euser.wishlist-destroy') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id: proId,
                // isActive: isActive,
                // isCert: isCert,
            },
            success: function(response) {
                // $(".cartCount").text(response.totalCartItems);
                alert(response.message);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    };
</script>
