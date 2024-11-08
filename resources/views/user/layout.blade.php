<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Home | Effective Gems')</title>
    <link rel="canonical" href="index.html" />
    <meta name="description" content="">
    <meta name="metakeyword" content="">
    <meta property="og:title" content="Home | Effective Gems" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="thumb/image.html" />
    <?php
    $setting = App\Models\Setting::first();
    echo $setting->header_script;
    ?>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- stylesheet -->
    <link rel="stylesheet" href="{{ url('/') }}/user/assets/css/bootstrap.css">
    <link href="{{ url('/') }}/user/assets/css/font.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/js/plugin/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/js/plugin/select2/select2.min.css" />
    <link rel="stylesheet" type="text/css"
        href="{{ url('/') }}/user/assets/js/plugin/airdatepicker/datepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/all.min.css?=5" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/mega-menu458e.css?=11.4" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/style552d.css?=47.4" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/aos.css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/easy-responsive-tabs.css?=7" />
    <!-- Fotorama -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ url('/') }}/user/assets/images/favicon34d9.png?=2" type="image/x-icon">
    <!--Google Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="bg-circles">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>
        <div class="circle circle-5"></div>
    </div>

    <!--header start-->
    <div class="as_main_wrapper">
        <section class="as_header_wrapper">
            <div class="top-header">
                <div class="container">
                    <div class="top-left">
                        <ul>
                            <li>
                                <div class="as_infobox">
                                    <span class="as_infoicon">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <div class="flex-one">
                                        <a href="tel:+91 7328835585">
                                            <span>+91 7328835585</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="as_infobox">
                                    <span class="as_infoicon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <div class="flex-one">
                                        <a href="mailto:gemseffective@gmail.com">
                                            <span>gemseffective@gmail.com</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="top-right">
                        <!--login and cart section start-->
                        <div class="menu-right">
                            <div class="header-login position-relative">
                                <a href="javascript:;"><i class="fa-light fa-circle-user"></i> Login/Signup</a>
                                <div class="header-login-dropdown">
                                    <ul>
                                        <li><a href="my-profile.html"><i class="fa-light fa-circle-user"></i> My
                                                Profile</a></li>
                                        <li><a href="my-order.html"><i class="fa-light fa-box-taped"></i> My Order</a>
                                        </li>
                                        <li><a href="wishlist.html"><i class="fa-light fa-heart"></i> Wishlist</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!--cart for desktop start-->
                            <div class="header-cart for-desktop"><a href="#" id="cartLink"
                                    onclick="if (parseInt(document.querySelector('.cartCount').textContent) > 0) { window.location.href = '/cart'; } else { alert('Your cart is empty!'); }"><i
                                        class="fa-light fa-cart-shopping"></i><span class="cartCount">0</span></a></div>
                            <!--cart for desktop end-->
                        </div>

                        <!--login and cart section end-->

                        <ul class="social-link">
                            <li><a href="{{$setting->fbLink}}" title="Facebook" target="_blank"><i
                                        class="fa-brands fa-facebook-f"></i></a>
                            </li>
                            <li><a href="{{$setting->twitterLink}}" title="Twitter" target="_blank"><i
                                        class="fa-brands fa-twitter"></i></a></li>
                            <li><a href="{{$setting->instaLink}}" title="Instagram" target="_blank"><i
                                        class="fa-brands fa-instagram"></i></a>
                            </li>
                            <li><a href="{{$setting->youtubeLink}}" title="YouTube" target="_blank"><i
                                        class="fa-brands fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <nav>
                <div class="container">
                    <div class="main-menu">
                        <div class="mobile-logo">
                            <a href="{{ route('user.index') }}"><img
                                    src="{{ url('/') }}/user/assets/images/logo.png"></a>
                        </div>
                        <div class="mobile_btn">
                            <i class="fas fa-bars"></i>
                        </div>
                        <div class="main_menu inline-menu">
                            <div class="as_info_detail mobile-none">
                                <div class="as_logo">
                                    <a href="{{ route('user.index') }}">
                                        <img src="{{ url('/') }}/user/assets/images/logo708a.png?=1"
                                            alt="">
                                    </a>
                                </div>
                            </div>
                            <ul class="menu-section">
                                <li><a href="{{ route('user.index') }}" class="active">Home</a></li>

                                @php
                                    // Fetch categories where onTop = 1
                                    $layoutcategories = \App\Models\Category::where('status', 1)
                                        ->where('onTop', 1)
                                        ->orderBy('sortOrder', 'asc')
                                        ->get();
                                @endphp
                                @foreach ($layoutcategories as $category)
                                    <li class="mega_menu_dropdown mega_menu_demo_2 has_dropdown"
                                        data-category-id="{{ $category->id }}">
                                        <a
                                            href="{{ route('user.categorywiseproduct', $category->id) }}">{{ $category->categoryName }}</a>
                                        <span class="arrow-icon"><i class="fas fa-angle-down"></i></span>

                                        {{-- Submenu container to be populated via AJAX --}}
                                        <div class="mega_menu sub_menu container" id="category-{{ $category->id }}">
                                            <div class="loading-spinner">Loading Please Wait...</div>
                                        </div>
                                    </li>
                                @endforeach

                                <li><a href="{{ route('user.index') }}">View All Categories</a></li>
                            </ul>


                        </div>

                        <!--search section start-->
                        <span class="right_btn">
                            <button type="button" class="btn_top" id="search-top">
                                <img src="{{ url('/') }}/user/assets/images/search.png" alt="search-image"
                                    title="Search">
                            </button>
                        </span>
                        <div class="search-button">
                            <div class="search-popup">
                                <div class="search-bg"></div>
                                <div class="search-form">
                                    <div class="container">
                                        <form action="{{ route('searchProducts') }}" method="GET">
                                            <div class="form row">
                                                <input type="hidden" name="catId" value="@yield('catId')">
                                                <input type="hidden" name="subCatId" value="@yield('subCatId')">
                                                <div class="col-10"> <input type="text" id="search"
                                                        name="search" placeholder="Type here to search..."></div>
                                                <div class="col-2">
                                                    <button type="submit" class="as_btn">Search</button>
                                                </div>

                                            </div>
                                        </form>
                                        <div class="closed_btn"><label for="search"><img
                                                    src="{{ url('/') }}/user/assets/images/cancel-white.png"
                                                    alt="image"></label></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--search section end-->

                        <!--cart for mobile start-->
                        <div class="header-cart for-mobile"><a href="#" id="cartLink"
                                onclick="if (parseInt(document.querySelector('.cartCount').textContent) > 0) { window.location.href = '/cart'; } else { alert('Your cart is empty!'); }"><i
                                    class="fa-light fa-cart-shopping"></i><span class="cartCount">0</span></a></div>
                        <!--cart for mobile end-->
                    </div>
                </div>
            </nav>
        </section>
    </div>
    <!--header end-->


    @yield('content')


    <!--footer start-->
    <section class="as_footer_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="as_footer_inner as_padderTop20 as_padderBottom20">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                                <div class="as_footer_widget">

                                    <img src="{{ url('/') }}/user/assets/images/logo-light.png" alt="image"
                                        class="footer-logo">
                                    <p>Effective gems is an online store targeting B2B Marketplace and the end
                                        customer...</p>
                                    <ul class="as_contact_list">
                                        <li>
                                            <img src="{{ url('/') }}/user/assets/images/svg/phone.svg"
                                                alt="">
                                            <p>
                                                <a href="tel:+91 7328835585">+91 7328835585</a>
                                            </p>
                                        </li>
                                        <li>
                                            <img src="{{ url('/') }}/user/assets/images/svg/mail.svg"
                                                alt="">
                                            <p>
                                                <a href="mailto:gemseffective@gmail.com">gemseffective@gmail.com</a>
                                            </p>
                                        </li>
                                        <li>
                                            <img src="{{ url('/') }}/user/assets/images/svg/map.svg"
                                                alt="">
                                            <a href="https://goo.gl/maps/sPAPcT8hU8fhtetH7" target="_blank">
                                                <p>Plot No - A/88, Saheed Nagar<br />
                                                    Bhubaneswar, Odisha - 751007</p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                <div class="as_footer_widget">
                                    <h3 class="as_footer_heading">Quick Links</h3>
                                    <ul>
                                        <li><a href="{{ route('user.index') }}"> Home</a></li>
                                        <li><a href="testimonial.html">Testimonial</a></li>
                                        <li><a href="faq.html"> FAQ</a></li>
                                        <li><a href="product.html"> Product</a></li>
                                        <li><a href="contact.html"> Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                <div class="as_footer_widget">
                                    <h3 class="as_footer_heading">Our Product</h3>
                                    <ul>
                                        @php
                                            // Fetch categories where onFooter = 1
                                            $footerCategories = \App\Models\Category::where('status', 1)
                                                ->where('onFooter', 1)
                                                ->orderBy('sortOrder', 'asc')
                                                ->get();
                                        @endphp

                                        {{-- Loop through the first half of categories --}}
                                        @foreach ($footerCategories->take(5) as $category)
                                            <li><a
                                                    href="{{ route('user.categorywiseproduct', $category->id) }}">{{ $category->categoryName }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                <div class="as_footer_widget mt_top">
                                    <ul>
                                        {{-- Loop through the second half of categories --}}
                                        @foreach ($footerCategories->slice(5) as $category)
                                            <li><a
                                                    href="{{ route('user.categorywiseproduct', $category->id) }}">{{ $category->categoryName }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="as_footer_widget">
                                    <h3 class="as_footer_heading">Our Newsletter</h3>
                                    <div class="as_newsletter_wrapper mt-3">
                                        <div class="as_newsletter_box">
                                            <input type="text" name="" id="" class="form-control"
                                                placeholder="Email...">
                                            <a href="javascript:;" class="as_btn">
                                                <img src="{{ url('/') }}/user/assets/images/svg/plane.svg"
                                                    alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="as_login_data">
                                        <label>I agree that my submitted data is being collected and stored.
                                            <input type="checkbox" name="as_remember_me" value="">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="middle-link-footer">
                        <?php $cou = count(App\Models\Page::where('status', 1)->get());
                        $xx = 0; ?>
                        @foreach (App\Models\Page::where('status', 1)->whereNotIn('id', [57])->get() as $pb)
                            <a href="/pages/{{ $pb->seoUrl }}">{{ $pb->pageName }}</a>
                            <?php $xx++; ?>
                            @if ($cou > $xx)
                                <span>||</span>
                            @endif
                        @endforeach
                    </div>


                    <div class="as_copyright_wrapper text-center">
                        <p>Copyright &copy; 2024. All Rights Reserved.</p>

                        <div class="develop-by">
                            <p>Developed by <a href="https://meinstyn.com/" target="_blank">Meinstyn Solutions</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--footer end-->

    <!--fixed action button start-->
    <div class="fixed-info">
        <a href="https://wa.me/+917328835585" target="_blank" class="info-list whatsapp">
            WhatsApp
            <span><i class="fa-brands fa-whatsapp"></i></span>
        </a>
        <a href="tel:+917328835585" target="_blank" class="info-list support">
            Support
            <span><i class="fa-solid fa-headset"></i></span>
        </a>
    </div>
    <!--fixed action button start-->


    <!-- javascript -->
    <script src="{{ url('/') }}/user/assets/js/jquery.js"></script>
    <script src="{{ url('/') }}/user/assets/js/bootstrap.js"></script>
    <script src="{{ url('/') }}/user/assets/js/plugin/slick/slick.min.js"></script>
    <script src="{{ url('/') }}/user/assets/js/plugin/select2/select2.min.js"></script>
    <script src="{{ url('/') }}/user/assets/js/plugin/countto/jquery.countTo.js"></script>
    <script src="{{ url('/') }}/user/assets/js/plugin/airdatepicker/datepicker.min.js"></script>
    <script src="{{ url('/') }}/user/assets/js/plugin/airdatepicker/i18n/datepicker.en.js"></script>
    <script src="{{ url('/') }}/user/assets/js/custom.js"></script>
    <script src="{{ url('/') }}/user/assets/js/mega-menu.js"></script>
    <script src="{{ url('/') }}/user/assets/js/aos.js"></script>
    <script src="{{ url('/') }}/user/assets/js/easy-responsive-tabs.js"></script>

    <!-- Fotorama -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

    <script src="../cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

    <script>
        $.ajax({
            type: "GET",
            url: "{{ route('viewCart') }}",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                $(".cartCount").text(response.totalCartItems);
                // $('#quantity-' + id).text(newQuantity);
                // toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                // toastr.error("An error occurred: " + error);
            },
        });
    </script>
    <script>
        AOS.init();

        AOS.init({
            disable: function() {
                var maxWidth = 991;
                return window.innerWidth < maxWidth;
            }
        });
    </script>

    <!-- search box start -->
    <script>
        $(document).ready(function() {
            $('#search-top').click(function() {
                $('.search-popup').css("display", "block");
            });
            $('.closed_btn').click(function() {
                $('.search-popup').hide();
            });
        });
    </script>
    <!-- search box end -->

    <script>
        function setenquery(p) {
            $("#product_name").val(p);
            $(".product_name").html(p);
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var cards = document.querySelectorAll('.card');

            cards.forEach(function(card) {
                card.addEventListener('mousemove', function(e) {
                    var rect = card.getBoundingClientRect(),
                        mouseX = e.clientX - rect.left - rect.width / 2,
                        mouseY = e.clientY - rect.top - rect.height / 2,
                        rotationX = (-1) * (mouseY / rect.height) * 20,
                        rotationY = (mouseX / rect.width) * 20;

                    card.style.transform =
                        `perspective(500px) rotateX(${rotationX}deg) rotateY(${rotationY}deg)`;
                });

                card.addEventListener('mouseleave', function() {
                    card.style.transform = '';
                });
            });
        });
    </script>

    <script>
        VanillaTilt.init(document.querySelectorAll(".card_tilt"), {
            max: 25,
            speed: 400,
            glare: true,
            "max-glare": 0.3
        });
    </script>

    <!--tab start-->
    <script>
        $(document).ready(function() {
            $('#horizontalTab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                closed: 'accordion', // Start closed if in accordion view
                activate: function(event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#tabInfo');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });

        });
    </script>
    <!--tab end-->

    <script>
        $(document).ready(function() {
            $('.mega_menu_dropdown').hover(function() {
                var categoryId = $(this).data('category-id');
                var targetDiv = $('#category-' + categoryId);

                // Check if products are already loaded
                if (!targetDiv.hasClass('products-loaded')) {
                    $.ajax({
                        url: '/layout-category-products/' +
                            categoryId, // Backend route to fetch products
                        method: 'GET',
                        success: function(response) {
                            // Populate the mega_menu with product data
                            targetDiv.html(response).addClass('products-loaded');
                        },
                        error: function(xhr, status, error) {
                            targetDiv.html('<p>Error loading products.</p>');
                        }
                    });
                }
            });
        });
    </script>


</body>

</html>
