@extends('user.layout')
@section('content')
@section('title',
    !empty($productdetails->metaTitle)
    ? $productdetails->metaTitle
    : $productdetails->productName .
    ' |
    Effective Gems')
@section('description',
    !empty($productdetails->metaDescription)
    ? $productdetails->metaDescription
    : $productdetails->description .
    ' |
    Effective Gems')
@section('image', asset($productdetails->image1))
<section class="container">
    <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-1.jpg');">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>{{ $productdetails->productName }}</h1>
                <ul class="breadcrumb">
                    <li><a href="{{ route('user.index') }}">Home</a></li>
                    <li><a
                            href="{{ route('user.categorywiseproduct', $productdetails->category->id) }}">{{ $productdetails->category->categoryName }}</a>
                    </li>
                    @if ($productdetails->subcategory)
                        <li>
                            <a href="{{ route('user.subCategory', $productdetails->subcategory->id) }}">
                                {{ $productdetails->subcategory->subCategoryName }}
                            </a>
                        </li>
                    @endif
                    <li>{{ $productdetails->productName }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="as_service_wrapper product-details-section as_breadcrum_top as_padderBottom40">
    <div class="container">
        <div class="row as_verticle_center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="as_product_box mt-0" data-aos="fade-right">
                            <div class="as_product_img">
                                <div class="fotorama" data-nav="thumbs">
                                    @php
                                        $images = [
                                            $productdetails->image1,
                                            $productdetails->image2,
                                            $productdetails->image3,
                                        ];
                                    @endphp

                                    @foreach ($images as $image)
                                        <a href="{{ asset($image) }}">
                                            <img src="{{ asset($image ?? 'defaultImage.jpeg') }}" alt="Product Image" />
                                        </a>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="as_product_box product-details-page">
                            <div class="as_product_detail details-height">
                                <h4 class="as_subheading" data-aos="fade-up">{{ $productdetails->productName }}</h4>
                                <input type="hidden" id="productCategory" value="{{ $productdetails->categoryId }}">

                                <div class="main-price">
                                    <span class="as_price" data-aos="fade-up">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        <span
                                            id="product-price">{{ $productdetails->priceB2C }}/{{ $productdetails->price_type }}</span>
                                    </span>
                                    <span class="mrp-price" data-aos="fade-up">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        {{ $productdetails->priceMRP }}/{{ $productdetails->price_type }}
                                    </span>

                                </div>



                                <div class="delivery-cost" data-aos="fade-up">
                                    Delivery Charges Apply
                                    @if ($couriertype)
                                        <input type="hidden" id="courierTypeId" value="{{ $couriertype->id }}">
                                        <input type="hidden" id="courierPrice"
                                            value="{{ $couriertype->courier_price }}">
                                        @if ($couriertype->courier_price == 0)
                                            Free
                                        @else
                                            <i class="fa-solid fa-indian-rupee-sign"></i>
                                            <span id="delivery-cost">{{ $productdetails->deliveryPrice }}</span>
                                        @endif
                                    @else
                                        Not available
                                    @endif
                                </div>


                                @if ($productdetails->categoryId == 1)
                                    <div class="quantity-select mt-2" data-aos="fade-up">
                                        <select name="quantity" id="quantityDd" class="form-select" required>
                                            <option value="">--Select Size (Carat/Ratti)--</option>
                                            @for ($i = $productdetails->min_product_qty; $i <= $productdetails->max_product_qty; $i += 0.5)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @else
                                    <div class="quantity-option mt-2" data-aos="fade-up">
                                        <span class="minus" id="minus" style="cursor: pointer;"><i
                                                class="fa-regular fa-minus"></i></span>
                                        <input type="number" id="quantity" value="1" min="1"
                                            name="quantity" readonly>
                                        <span class="plus" id="plus" style="cursor: pointer;"><i
                                                class="fa-regular fa-plus"></i></span>
                                    </div>
                                @endif



                                @php
                                    $activation = App\Models\Activations::where(
                                        'id',
                                        $productdetails->activationId,
                                    )->first();
                                    $Certification = App\Models\Certification::where(
                                        'id',
                                        $productdetails->certificationId,
                                    )->first();
                                @endphp

                                @if (!($activation && $activation->id == 2) || !($Certification && $Certification->id == 2))
                                    <div class="extra-checkbox" data-aos="fade-up">
                                        @if (!($activation && $activation->id == 2))
                                            <div class="data-check">
                                                <label>

                                                    Activation (+{{ $activation->amount ?? 'N/A' }})
                                                    <input type="checkbox" id="activationCheckbox" name="is_act"
                                                        value="1" data-price="{{ $activation->amount ?? 0 }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        @endif
                                        @if (!($Certification && $Certification->id == 2))
                                            <div class="data-check">
                                                <label>

                                                    Certification (+{{ $Certification->amount ?? 'N/A' }})
                                                    <input type="checkbox" id="certificationCheckbox" name="is_cert"
                                                        value="1" data-price="{{ $Certification->amount ?? 0 }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        @endif

                                    </div>
                                @endif




                                <div class="total-price-details" data-aos="fade-up">
                                    <span>Total :</span>

                                    <input type="hidden" id="productPrice" value="{{ $productdetails->priceB2C }}">
                                    <span class="as_price">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        <span id="product-price-total">{{ $productdetails->priceB2C }}</span>
                                    </span>
                                </div>


                                <!--button start-->
                                <div class="main-btn mt-2 space-between justify-content-start" data-aos="zoom-in">
                                    <a href="javascript:;" onclick="return buyNow({{ $productdetails->id }})"
                                        class="as_btn"><span>Buy Now</span></a>
                                    <a href="javascript:;" class="as_btn-2 btn-res"
                                        onclick="return addToCart({{ $productdetails->id }})"><span>Add
                                            to Cart</span>
                                    </a>
                                    <a href="javascript:;" class="wishlist-btn-details" title="Add to Wishlist"
                                        onclick="return addToWishlist({{ $productdetails->id }})"><i
                                            class="fa-light fa-heart"></i></a>
                                </div>
                                <!--button end-->

                                <ul class="short-note">
                                    {!! $productdetails->productDesc1 !!}
                                </ul>


                                <!--product variant start-->
                                @if (count($variants) != 0)
                                    <div class="product-variant-section">
                                        <h3>Product Variant :</h3>
                                        @foreach ($variants as $variant)
                                            <div class="variant-item">
                                                @if (!empty($variant->prodid))
                                                    <a
                                                        href="{{ route('user.productdetails', ['prodid' => $variant->prodid]) }}">
                                                        <h4>{{ $variant->variantName ?? '' }}<br>₹<strong>{{ $variant->priceB2C ?? '' }}</strong>
                                                        </h4>
                                                    </a>
                                                @else
                                                    <h4>{{ $variant->variantName ?? 'N/A' }}<br>₹<strong>{{ $variant->priceB2C ?? '' }}</strong>
                                                    </h4>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif


                                <div class="clearfix">&nbsp;</div>
                                <!--product variant end-->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Start product details tab section -->
<section class="as_padderBottom40">
    <div class="container">
        <div id="horizontalTab">
            <ul class="resp-tabs-list">
                <li>{{ $productdetails->productHeading2 ?? 'Description' }}</li>
                <li>{{ $productdetails->productHeading3 ?? 'Shipping Policy' }}</li>
                <li>{{ $productdetails->productHeading4 ?? 'Return Policy' }}</li>
                <li>{{ $productdetails->productHeading5 ?? 'Payment Method' }}</li>
            </ul>
            <div class="resp-tabs-container">
                <div>
                    <p>{!! $productdetails->productDesc2 ?? 'No Description Available' !!}</p>
                </div>

                <!--tab 1 end-->
                <div>
                    @if ($productdetails->productDesc3)
                        {!! $productdetails->productDesc3 !!}
                    @else
                        <p>Worldwide Shipping is available.<br>
                            1. Free shipping on orders over INR 5,000 in India.<br>
                            2. COD is available for orders over INR 5,000 in India.<br>
                            3. International Express Shipping takes 4-7 days for delivery.</p>
                    @endif
                </div>
                <!--tab 2 end-->
                <div>
                    @if ($productdetails->productDesc4)
                        {!! $productdetails->productDesc4 !!}
                    @else
                        <p> 1. Get 100% moneyback on returning loose gemstones within 10 days for a full refund of the
                            gemstone price.<br>
                            2. Return Shipment is at customer's cost.<br>
                            3. Shipping Charges, GST/VAT and duties are not refundable.</p>
                    @endif

                </div>
                <!--tab 3 end-->
                <div>
                    @if ($productdetails->productDesc5)
                        {!! $productdetails->productDesc5 !!}
                    @else
                        <p> 1. Credit Cards: All Visa, MasterCard and American Express Credit Cards are accepted<br>
                            2. Debit Cards (India): All Visa and Maestro Debit Cards are accepted.<br>
                            3. PayPal, Net Banking, Cash Cards</p>
                    @endif
                </div>
                <!--tab 4 end-->
            </div>
        </div>
    </div>
</section>
<!-- End product details tab section -->

<!--Related Products start-->

<!-- Related Products start -->
@if ($relatedProducts->count() > 0)
    <section class="as_product_wrapper as_padderBottom40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 text-center">
                    <div class="inline-header">
                        <h1 class="as_heading">Related Products</h1>
                        <div class="text-center" data-aos="zoom-in">
                            <a href="product.html" class="as_btn">view more</a>
                        </div>
                    </div>
                    <div class="row mt-2" data-aos="fade-down" data-aos-duration="1500">
                        @foreach ($relatedProducts as $related)
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <div class="as_product_box">
                                    <a href="{{ route('user.productdetails', $related->id) }}"
                                        class="as_product_img">
                                        <img src="{{ asset($related->image1 ?? 'defaultImage.jpeg') }}"
                                            class="img-responsive" alt="Product Image" />
                                    </a>
                                    <div class="as_product_detail">
                                        <h4 class="as_subheading">{{ $related->productName }}</h4>
                                        <span class="as_price">
                                            <i class="fa-solid fa-indian-rupee-sign"></i>
                                            <span
                                                style="text-decoration: line-through;">{{ $related->priceMRP }}</span>
                                            <span>{{ $related->priceB2C }} / {{ $related->price_type }}</span>
                                        </span>

                                        <div class="space-between">
                                            <a href="{{ route('user.productdetails', $related->id) }}"
                                                class="as_btn_cart"><span>View Details</span></a>
                                            <a href="javascript:;" class="enquire_btn" data-bs-toggle="modal"
                                                data-bs-target="#enquire_modal"><span>Order Now</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!-- Related Products end -->

<!-- Popular Products start -->
@if ($popularproducts->count() > 0)
    <section class="as_product_wrapper as_padderBottom40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 text-center">
                    <div class="inline-header">
                        <h1 class="as_heading">Popular Products</h1>
                        <div class="text-center" data-aos="zoom-in">
                            <a href="popular-product.html" class="as_btn">view more</a>
                        </div>
                    </div>
                    <div class="row mt-2" data-aos="fade-down" data-aos-duration="1500">
                        @foreach ($popularproducts as $popular)
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <div class="as_product_box">
                                    <a href="{{ route('user.productdetails', $popular->id) }}"
                                        class="as_product_img">
                                        <img src="{{ asset($popular->image1 ?? 'defaultImage.jpeg') }}"
                                            class="img-responsive" alt="Product Image" />
                                    </a>
                                    <div class="as_product_detail">
                                        <h4 class="as_subheading">{{ $popular->productName }}</h4>
                                        <span class="as_price">
                                            <i class="fa-solid fa-indian-rupee-sign"></i>
                                            <span
                                                style="text-decoration: line-through;">{{ $popular->priceMRP }}</span>
                                            <span>{{ $popular->priceB2C }} / {{ $popular->price_type }}</span>
                                        </span>

                                        <div class="space-between">
                                            <a href="{{ route('user.productdetails', $popular->id) }}"
                                                class="as_btn_cart"><span>View Details</span></a>
                                            <a href="javascript:;" class="enquire_btn" data-bs-toggle="modal"
                                                data-bs-target="#enquire_modal"><span>Order Now</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!--Popular Products end-->
@endsection
<script>
    const addToWishlist = (proId) => {

        // var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
        // var isActive = $('input[name="is_act"]').is(':checked') ? $('input[name="is_act"]').val() : 0;
        // var isCert = $('input[name="is_cert"]').is(':checked') ? $('input[name="is_cert"]').val() : 0;
        $.ajax({
            type: "POST",
            url: "{{ route('euser.wishlist-add') }}",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: proId,
                // quantity: quantity,
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
    const addToCart = (proId) => {

        var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
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
                alert(response.message);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    };
    const buyNow = (proId) => {

        var quantity = parseFloat($('input[name="quantity"], select[name="quantity"]').val());
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
                // alert(response.message);
                window.location.href = '/checkout';
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    };
</script>
