@extends('user.layout')
@section('content')
    @include('user.partials.banner')
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
                                <a href="popular-product.html" class="as_btn">view more</a>
                            </div>
                        </div>
                        <div class="row mt-2" data-aos="fade-down" data-aos-duration="1500">
                            @foreach ($popularproducts as $popular)
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <div class="as_product_box">
                                        <a href="{{ route('user.productdetails', $popular->id) }}" class="as_product_img">
                                            <img src="{{ asset($popular->image1 ?? 'defaultImage.jpeg') }}"
                                                class="img-responsive" alt="product Image" />
                                        </a>
                                        <div class="as_product_detail">
                                            <h4 class="as_subheading">{{ $popular->productName }}</h4>
                                            <span class="as_price">
                                                <i class="fa-solid fa-indian-rupee-sign"></i>
                                                <span style="text-decoration: line-through;">{{ $popular->priceMRP }}</span>
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
                                    <span data-from="0" data-to="50" data-speed="5000">1000+</span>
                                    <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                                </span>
                                <h4>Genuine Products</h4>
                            </div>
                        </li>
                        <li>
                            <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                                <span class="as_number">
                                    <span data-from="0" data-to="50" data-speed="5000">1K+</span>
                                    <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                                </span>
                                <h4>Astrologer Connected</h4>
                            </div>
                        </li>
                        <li>
                            <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                                <span class="as_number">
                                    <span data-from="0" data-to="50" data-speed="5000">1K+</span>
                                    <img src="{{ url('/') }}/user/assets/images/svg/choose.svg" alt="">
                                </span>
                                <h4>Jewellery Showroom</h4>
                            </div>
                        </li>
                        <li>
                            <div class="as_whychoose_box text-center" data-aos="zoom-in" data-aos-duration="1500">
                                <span class="as_number">
                                    <span data-from="0" data-to="50" data-speed="5000">100K+</span>
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
                                <a href="" class="as_btn">View More</a>
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
                    <a href="faq.html" class="as_btn">view more</a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">
                    <div class="as_widget as_faq_widget">
                        <div class="accordion as_accordion" id="accordionPanelsStayOpenExample" data-aos="fade-up"
                            data-aos-duration="1500">
                            <div class="row">
                                <div class="col-lg-6 col-xs-12">
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-15"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-15">
                                                    Do You Provide A Gem Authenticity Certificate?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-15" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Our gemstones are tested on bulk size. We can show you that certificate.
                                                    For your product,, you can test it on any of the testing labs and
                                                    compare it with our certificate.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-14"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-14">
                                                    Are your gemstones genuine &amp; natural?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-14" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Absolutely Yes! You can be 100% sure when you buy a gemstone or diamond
                                                    from Nine Gems. Our buying &amp; testing offices are well equipped with
                                                    all gem testing resources and experienced Gem-A / GIA qualified
                                                    gemmologists &amp; diamantaires for efficient gem testing. It is only
                                                    because of these standards &amp; sophisticated environment, that we can
                                                    guarantee the authenticity of every gemstone &amp; diamond
                                                    merchandise.&nbsp;</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-13"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-13">
                                                    What is the difference between Carat &amp; Ratti?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-13" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Carat (0.200mg) is the standard unit of weight measurement for gemstones
                                                    and diamonds around the world. Whereas Ratti (0.1215mg / 0.182mg) is a
                                                    traditional unit of measurement in India based on Ratti Seed, which was
                                                    used a long time ago when accurate weighing scales did not exist. Ratti
                                                    is still used by many astrologers in India to recommend
                                                    gemstones.&nbsp;</p>

                                                <p>We often recommend our customers to get the required gem weight in carats
                                                    as there are few variations in Ratti (Sunari ratio &amp; Pakki Ratti).
                                                    Astrologers from different regions of India use different calculations
                                                    for recommending gemstones.</p>

                                                <p>1 Carat = 0.200 milligrams</p>

                                                <p>1 Ratti = 0.1215 milligrams (Sunari Ratti)</p>

                                                <p>1 Ratti = 0.1822 milligrams (Pakki Ratti)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-12"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-12">
                                                    Is Carat related to the purity of gemstone?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-12" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>No, it is not related to the purity of a gemstone. Carat only defines the
                                                    Weight of a gemstone or diamond. It is often confused with
                                                    the&nbsp;<em>Karat</em>&nbsp;of
                                                    Gold or other precious metals that defines the purity of the metal.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-11"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-11">
                                                    What is Carat?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-11" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Carat is the standard unit of weight around the world in which a gemstone
                                                    or diamond is measured. Carat originated from the Latin word
                                                    &quot;Carratus&quot;
                                                    which relates to Carob Bean (seed).</p>

                                                <p>&nbsp;</p>

                                                <p>1 Carat = 200 milligrams = 0.200 grams</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xs-12">
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-10"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-10">
                                                    Why are natural gemstones expensive?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-10" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Gem mines are not found all over the world, but they are discovered only
                                                    in few locations. It might take millions of years for gemstones to form
                                                    under the earth&#39;s crust. Moreover, it might take another million
                                                    years to discover gemstone and diamond mines around the world. Out of
                                                    the total gemstones extracted from these mines approximately 1/100th are
                                                    of Gem Quality and out of these less than 5% are more than 3
                                                    carats.<br />
                                                    Thus finding a clear-transparent and beautifully colored gemstone is
                                                    extremely rare.</p>

                                                <p>Gemstones travel a long journey before they reach us. How much are you
                                                    paying for such a precious gemstone? Are they really expensive? Prices
                                                    are mere numbers and can never define the real value of a natural
                                                    gemstone.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-9"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-9">
                                                    How do I know if my gemstone or diamond is genuine?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-9" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Unfortunately, you&#39;ll not. Only an experienced gemologist or
                                                    diamantaire can distinguish genuine gemstones from synthetics,
                                                    imitations, or fake ones. For a layman, certifications &amp; trust are
                                                    important factors that you must look for while buying gemstones from any
                                                    merchant.&nbsp;</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-8"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-8">
                                                    Is There Anything Beyond 4C&#039;s I Should Look For?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-8" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Absolutely Yes! There&#39;s much more than 4C&#39;s in gemstones &amp;
                                                    diamonds which are looked at by a Gemologist to grade &amp; value a gem.
                                                    Few of them are as follows:</p>

                                                <p>&gt;<strong>Enhancements:</strong>&nbsp;Most of the gemstones and
                                                    diamonds are treated or enhanced by humans to improve the color and
                                                    clarity of a gemstone. Treatments might improve the appearance of a
                                                    gemstone, but Untreated gemstones win over the rarity factor. Therefore
                                                    un-enhanced gemstones dominate the market price of any gemstone.&nbsp;
                                                </p>

                                                <p>&gt;<strong>Characteristics:</strong>&nbsp;This is one of the most
                                                    important factors which differentiates colored gemstones from Diamonds.
                                                    &quot;<em>Beauty is in the eyes of its Beholder</em>&quot; - The
                                                    character of every gemstone is different and unique. A gemstone that
                                                    might appeal to someone might not appeal to another person.&nbsp;</p>

                                                <p>&gt;<strong>Certification:</strong>&nbsp;A gemstone needs to be certified
                                                    as there are many synthetics &amp; natural imitations which closely
                                                    resemble their natural counterparts. Moreover, most natural gemstones
                                                    are also enhanced with treatments that are difficult to identify by a
                                                    layman. Only an authentic certificate can assist a customer with all the
                                                    details required.</p>

                                                <p>&gt;<strong>Origin:</strong>&nbsp;The origin of a gemstone also plays an
                                                    important role many times. Few origins have a premium &quot;Brand
                                                    Value&quot;
                                                    compared to others due to many factors. Example: Kashmir Sapphire,
                                                    Columbian Emeralds, and Burmese Rubies are priced way higher than
                                                    similar-looking gemstones from other origins.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-7"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-7">
                                                    What Are Four C&#039;s in Gemstones &amp; Diamonds?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-7" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>The Four C&#39;s -&nbsp;Color, Clarity, Cut &amp; Carat Weight&nbsp;are
                                                    the most important factors which determine the value &amp; quality of a
                                                    gemstone.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="panelsStayOpen-headingOne">
                                            <h2 class="mb-0">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-6"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-6">
                                                    What Are Inclusions?
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="panelsStayOpen-6" class="accordion-collapse collapse "
                                            aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body">
                                                <p>Inclusions are naturally formed minerals, fingerprints, fissures, or any
                                                    other feature which are within the gemstone and are usually sculpt at
                                                    the time formation under the earth&#39;s crust. These inclusions might
                                                    sometimes reach the surface of the gemstone in the form of cavities,
                                                    inverted crystals, etc.&nbsp;</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--FAQ end-->
@endsection
