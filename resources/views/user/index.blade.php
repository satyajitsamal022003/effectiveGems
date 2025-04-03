@extends('user.layout')
@section('content')
{{-- @include('user.partials.banner') --}}
@if ($banners->count() > 0)
<section class="banner-section position-relative">
    <div class="owl-carousel banner-owl right-dots">
        @foreach ($banners as $banner)
        <div class="banner-item" style="background-image: url('{{ asset($banner->image ? $banner->image : '/user/assets/images/banner.jpg') }}');">
            <div class="banner-text">
                <div class="container">
                    <h1 class="main-head">{{ $banner->title }}</h1>
                    <p>{{ $banner->description }}</p>
                    <div class="inline-main-btn">
                        <a href="{{ $banner->button_link }}" class="as_btn aos-init aos-animate" data-aos="zoom-in">{{ $banner->button_text }} </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@else
<section class="banner-section position-relative">
    <div class="owl-carousel banner-owl right-dots">
        <div class="banner-item" style="background-image: url(/user/assets/images/banner.jpg)">
            <div class="banner-text">
                <div class="container">
                    <h1 class="main-head">Buy High Quality Natural Gemstones At Wholesale Prices. B2B Marketplace</h1>
                    <div class="inline-main-btn">
                        <a href="#" class="as_btn aos-init aos-animate" data-aos="zoom-in">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <!--banner item end-->
        <div class="banner-item" style="background-image: url(/user/assets/images/banner.jpg)">
            <div class="banner-text">
                <div class="container">
                    <h2 class="main-head">Buy High Quality Natural Gemstones At Wholesale Prices. B2B Marketplace</h2>
                    <div class="inline-main-btn">
                        <a href="#" class="as_btn aos-init aos-animate" data-aos="zoom-in">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <!--banner item end-->
    </div>
</section>
@endif


<div class="clearfix"></div>
<!--category start-->
<section class="as_padderTop40 as_padderBottom40 category-section">
    <div class="container">
        <div class="category-area">
            @foreach ($categories as $category)
            <a href="{{ route('user.categorywiseproduct', $category->id) }}"
                class="category-box animate__fadeInLeft" data-aos="zoom-in" data-aos-duration="1500">
                <div class="category-img">
                    <img src="{{ asset($category->image ?? 'defaultImage.jpeg') }}" alt="Category Image" />
                </div>
                <div class="category-text">
                    <h4>{{ $category->categoryName }}</h4>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
<!--category end-->


<!--popular product start-->
@if ($popularproducts->count() > 0)
<section class="as_product_wrapper as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 text-center">
                <div class="inline-header" data-aos="zoom-in" data-aos-duration="1500">
                    <h1 class="as_heading">Popular Products</h1>
                    <div class="text-center" data-aos="zoom-in">
                        <a href="{{route('user.popularproducts')}}" class="as_btn">view more</a>
                    </div>
                </div>
                <div class="row mt-2" data-aos="fade-down" data-aos-duration="1500">
                    @foreach ($popularproducts as $popular)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="as_product_box {{ $popular->out_of_stock == 1 ? 'out-of-stock' : '' }}">
                            <a href="{{ route('user.productdetails', $popular->id) }}" class="as_product_img">
                                <img src="{{ asset($popular->image1 ?? 'defaultImage.jpeg') }}"
                                    class="img-responsive" alt="product Image" />
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
                                    @if ($popular->out_of_stock == 1)
                                    <button type="button" style="border-radius: 25px;" class="btn btn-secondary">Out of Stock</button>
                                    @else
                                    <a href="javascript:;" class="enquire_btn"
                                        onclick="buyNow({{ $popular->id }})"><span>Order Now</span></a>
                                    @endif
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
<!--popular product end-->


<!--step start-->
<section class="as_padderTop40 as_padderBottom40 step_mobile">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="step step1_bg" data-aos="fade-right" data-aos-duration="1500">
                    <img src="{{ url('/') }}/user/assets/images/favorites.png" alt="image">
                    <h2>Genuine Product</h2>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="step step1_bg" data-aos="fade-up" data-aos-duration="1500">
                    <img src="{{ url('/') }}/user/assets/images/positive.png" alt="image">
                    <h2>Lowest Price</h2>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="step step1_bg" data-aos="fade-left" data-aos-duration="1500">
                    <img src="{{ url('/') }}/user/assets/images/24-hours.png" alt="image">
                    <h2>Best Service</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!--step end-->


<!--about us start-->
<section class="as_about_wrapper as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="as_aboutimg text-right" data-aos="fade-right" data-aos-duration="1500">

                    <img src="{{ url('/') }}/user/assets/images/planets-1.png" alt="image"
                        class="img-responsive animate-up-down">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="as_about_detail" data-aos="fade-left" data-aos-duration="1500">
                    <h1 class="as_heading mb-3">About Effective Gems</h1>
                    <p><strong>&nbsp;Effective Gems</strong> is one of the best wholesalers of Astrology and Vastu
                        products since 1997. On a thought to provide the best and genuine products at the best
                        comparative price, Effective Gems is started and successfully delivering customer satisfaction
                        since 27 years.</p>

                    <p>&nbsp;Effective Gems started its journey from a small gemstone shop at Bhubaneswar, Odisha. Now
                        it is one of the leading B2B astrology and vastu products distributors in the country. Effective
                        gems is doing business around all the states and major cities in Odisha.</p>

                    <ul>
                    </ul>

                    <p>Effective gems is a trusted brand of Vastu and Astrology Materials. It deals with 100% genuine
                        and lab-tested materials. You can blindly trust for quality and price.</p>

                    <p>Effective gems is listed on almost all the major marketplace of India like Indiamart, Facebook,
                        Amazon, facebook Etc.</p>
                </div>
            </div>
        </div>

        <div class="our-product" data-aos="fade-down" data-aos-duration="1500">
            <h1 class="text-white mt-3 new-font">Our Major Products</h1>
            <div class="as_paragraph_wrapper mb-3">
                <ul>
                    @foreach (App\Models\Category::where('status', 1)->get() as $majorprods)
                    <li><a
                            href="{{ route('user.categorywiseproduct', $majorprods->id) }}">{{ $majorprods->categoryName }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- <a href="about.html" class="as_btn btn_two mt-3">Read More</a> -->
        </div>
    </div>
</section>
<!--about us end-->


<!--count start-->
<section class="as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="as_whychoose_box-img">
                    <img src="{{ url('/') }}/user/assets/images/2150771723.jpg" alt="image"
                        class="animate-up-down">
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <ul class="as_choose_ul"
                    style="background-image: url('{{ url('/') }}/user/assets/images/bg-content-section-astro.png')">
                    <li>
                        <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                            <span class="as_number">
                                <span class="count" data-to="1000" data-suffix="+">0</span>
                                <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                            </span>
                            <h4>Genuine Products</h4>
                        </div>
                    </li>
                    <li>
                        <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                            <span class="as_number">
                                <span class="count" data-to="1" data-suffix="K+">0</span>
                                <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                            </span>
                            <h4>Astrologer Connected</h4>
                        </div>
                    </li>
                    <li>
                        <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                            <span class="as_number">
                                <span class="count" data-to="1" data-suffix="K+">0</span>
                                <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                            </span>
                            <h4>Jewellery Showroom</h4>
                        </div>
                    </li>
                    <li>
                        <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                            <span class="as_number">
                                <span class="count" data-to="100" data-suffix="K+">0</span>
                                <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                            </span>
                            <h4>Satisfied Buyers</h4>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--count end-->


<!--testimonial start-->
@if ($testimonials->count() > 0)
<section class="as_customer_wrapper as_padderBottom40 as_padderTop40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="inline-header" data-aos="zoom-in" data-aos-duration="1500">
                    <h1 class="as_heading">What My Clients Say</h1>
                    <div class="text-center">
                        <a href="{{route('user.testimonials')}}" class="as_btn">View More</a>
                    </div>
                </div>

                <div class="as_customer_slider mt-5">
                    <div class="as_customer_for">
                        @foreach ($testimonials as $testimonial)
                        @if ($testimonial->status == 1)
                        <div class="as_customer_content" data-aos="flip-left" data-aos-duration="1500">
                            <div class="flex_text">
                                <img src="{{ $testimonial->userImage ? asset($testimonial->userImage) : url('/user/assets/images/profile_user.jpg') }}"
                                    alt="image">
                                <h3>{{ $testimonial->userName }} -
                                    <span>{{ $testimonial->designation }}</span>
                                </h3>
                            </div>
                            <p>{!! $testimonial->description !!}</p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!--testimonial end-->


<!--FAQ start-->
<section class="as_blog_wrapper as_padderBottom40">
    <div class="container">
        <div class="inline-header as_padderBottom40" data-aos="zoom-in" data-aos-duration="1500">
            <h1 class="as_heading">Frequently Asked Questions</h1>
            <div class="text-center">
                <a href="{{route('user.faqs')}}" class="as_btn">View More</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">
                <div class="as_widget as_faq_widget">
                    <div class="accordion as_accordion" id="accordionPanelsStayOpenExample" data-aos="fade-up"
                        data-aos-duration="1500">
                        <div class="row">
                            @foreach ($faqs->chunk(ceil($faqs->count() / 2)) as $faqChunk)
                            <div class="col-lg-6 col-xs-12">
                                @foreach ($faqChunk as $index => $faq)
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading{{ $faq->id }}">
                                        <h2 class="mb-0">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $faq->id }}"
                                                aria-expanded="false"
                                                aria-controls="collapse{{ $faq->id }}">
                                                {{ $faq->question }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $faq->id }}">
                                        <div class="accordion-body">
                                            <p>{!! $faq->answer !!}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function animateCount(el, start, end, duration, suffix) {
            let range = end - start;
            let current = start;
            let increment = range / (duration / 50);
            let timer = setInterval(function() {
                current += increment;
                el.innerText = Math.round(current).toLocaleString() + suffix; // Format number with suffix
                if (current >= end) {
                    el.innerText = end + suffix;
                    clearInterval(timer);
                }
            }, 50);
        }

        document.querySelectorAll('.count').forEach(el => {
            let target = parseInt(el.getAttribute('data-to'));
            let suffix = el.getAttribute('data-suffix') || ''; // Get suffix like "K+"
            animateCount(el, 0, target, 2000, suffix); // Adjust duration (2000ms = 2 seconds)
        });
    });
</script>
<!--FAQ end-->
@endsection