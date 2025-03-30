@extends('user.layout')
@section('content')
@section('title', 'Popular Products | Effective Gems')
@section('description', 'Discover the most popular products on Effective Gems.')
@section('image', asset('user/assets/images/popular-products.jpg'))

<section class="container">
    <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-2.jpg');">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Popular Products</h1>
                <ul class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>Popular Products</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="as_blog_wrapper as_padderBottom40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 text-center">
                <div class="row mt-2" data-aos="fade-down" data-aos-duration="1500">
                    @foreach ($popularproducts as $product)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="as_product_box">
                                <a href="{{ route('user.productdetails', $product->id) }}" class="as_product_img">
                                    <img src="{{ asset($product->image1 ?? 'defaultImage.jpeg') }}"
                                        alt="{{ $product->productName }}" class="img-responsive">
                                </a>
                                <div class="as_product_detail">
                                    <h4 class="as_subheading">{{ $product->productName }}</h4>
                                    <span class="as_price">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        <span style="text-decoration: line-through;">{{ $product->priceMRP }}</span>
                                        <span>{{ $product->priceB2C }} / {{ $product->price_type }}</span>
                                    </span>
                                    <div class="space-between">
                                        <a href="{{ route('user.productdetails', $product->id) }}"
                                            class="as_btn_cart"><span>View Details</span></a>
                                        @if ($product->out_of_stock == 1)
                                            <button type="button" style="border-radius: 25px;" class="btn btn-secondary">Out of Stock</button>
                                        @else
                                            <a href="javascript:;" class="enquire_btn"
                                                onclick="buyNow({{ $product->id }})"><span>Order Now</span></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="pagination-bottom" data-aos="fade-up">
            {{ $popularproducts->links() }}
        </div>
    </div>
</section>

<script>
    function buyNow(proId) {
        var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val()) || 1;
        var isActive = $('input[name="is_act"]').is(':checked') ? $('input[name="is_act"]').val() : 0;
        var isCert = $('input[name="is_cert"]').is(':checked') ? $('input[name="is_cert"]').val() : 0;

        $.ajax({
            type: "POST",
            url: "{{ route('addToCart') }}",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: proId,
                quantity: quantity,
                isActive: isActive,
                isCert: isCert,
            },
            success: function(response) {
                $(".cartCount").text(response.totalCartItems);
                window.location.href = '/checkout';
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + error);
            },
        });
    }
</script>
@endsection
