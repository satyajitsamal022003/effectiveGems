@extends('user.layout')
@section('content')
@section('title', !empty($category->metaTitle) ? $category->metaTitle : $category->subCategoryName . ' |
    Effective Gems')

@section('subCatId', $category->id)


<section class="container">
    <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-1.jpg');">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Sub category</h1>
                <ul class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>{{ $category->subCategoryName }}</li>
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
                    @foreach ($subcategoryproducts as $subcat)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="as_product_box">
                                <a href="{{ route('user.productdetails', $subcat->id) }}" class="as_product_img">
                                    <img src="{{ asset($subcat->image1) }}" alt="{{ $subcat->productName }}"
                                        class="img-responsive">
                                </a>
                                <div class="as_product_detail">
                                    <h4 class="as_subheading">{{ $subcat->productName }}</h4>
                                    <span class="as_price">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        <span style="text-decoration: line-through;">{{ $subcat->priceMRP }}</span>
                                        <span>{{ $subcat->priceB2C }} / {{ $subcat->price_type }}</span>
                                    </span>
                                    <div class="space-between">
                                        <a href="{{ route('user.productdetails', $subcat->id) }}"
                                            class="as_btn_cart"><span>View Details</span></a>
                                        <a href="javascript:;" class="enquire_btn"
                                            onclick="buyNow({{ $subcat->id }})"><span>Order Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="pagination-bottom" data-aos="fade-up">
            {{ $subcategoryproducts->links() }}
           
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
