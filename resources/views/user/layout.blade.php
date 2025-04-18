<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Home | Effective Gems')</title>
    <link rel="canonical" href="index.html" />
    <meta name="description" content="">
    <meta name="metakeyword" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ Auth::guard('euser')->check() ? '1' : '' }}">
    <meta name="auth-checked" content="{{ Auth::guard('euser')->check() ? 'true' : 'false' }}">
    <meta property="og:title" content="@yield('title', 'Home | Effective Gems')" />
    <meta property="og:description" content="@yield('description', '')" />
    <meta property="og:image" content="@yield('image', 'thumb/image.html')" />
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
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/mega-menu458e.css?=11.5" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/style552d.css?=51.2" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/aos.css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/easy-responsive-tabs.css?=7" />
    <!-- Owl carousel-->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/user/assets/css/owl.carousel.min.css" />
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
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <style>
        /* Styling for suggestions */
        .suggestions-list {
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            background-color: #003399;
            width: calc(100% - 30px);
            z-index: 1000;
        }

        .suggestions-list li {
            padding: 10px;
            cursor: pointer;
        }

        .suggestions-list li a {
            color: #fff;
        }
        .suggestions-list li:hover a {
            color: #003399;
        }

        .suggestions-list li:hover {
            color: #003399;
            background-color: #ffffff;
        }
    </style>
    <style>
        .slider-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            background-color: #e6b317;
        }

        .slider-content {
            display: inline-block;
            animation: slide 20s linear infinite;
        }

        .slider-item {
            display: inline-block;
            padding: 0 40px; /* Increased padding for spacing */
            font-size: 18px;
            color: #000;
        }

        @keyframes slide {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
    </style>
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
            @if ($annoument_text && $annoument_text->announcement_text && $annoument_text->announcement_status)
            <div class="col-md-12 mx-auto slider-container" id="announcement-slider">
                <div class="slider-content">
                    <span class="slider-item">{{ $annoument_text->announcement_text }}</span>
                </div>
            </div>
            @endif
            
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
                                    <a href="tel:{{ $setting->phone1 ?? '+91 7328835585' }}">
                                        <span>{{ $setting->phone1 ?? '+91 7328835585' }}</span>
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
                                    <a href="mailto:{{ $setting->email1 ?? 'gemseffective@gmail.com' }}">
                                        <span>{{ $setting->email1 ?? 'gemseffective@gmail.com' }}</span>
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
                                @guest('euser')
                                    <a href="{{ route('eusers.login') }}"><i class="fa-light fa-circle-user"></i> Login</a>
                                    /
                                    <a href="{{ route('eusers.signup') }}"><i class="fa-light fa-circle-user"></i>
                                        Signup</a>
                                @endguest
                                @if (Auth::guard('euser')->check())
                                    <div class="header-login-dropdown-wrapper">
                                        <button id="userDropdownToggle" class="dropdown-toggle">
                                            <i class="fa-light fa-circle-user"></i> My Account
                                        </button>
                                        <div id="userDropdownMenu" class="header-login-dropdown hidden">
                                            <ul>
                                                <li><a href="{{ route('euser.myProfile') }}"><i
                                                            class="fa-light fa-circle-user"></i> My Profile</a></li>
                                                <li><a href="{{ route('euser.myorderlist') }}"><i
                                                            class="fa-light fa-box-taped"></i> My Order</a></li>
                                                <li><a href="{{ route('euser.wishlist') }}"><i
                                                            class="fa-light fa-heart"></i> Wishlist</a></li>
                                                <li><form action="{{ route('euser.logout') }}" method="POST">
                                                                @csrf
                                                                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">
                                                                    <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                                                                </button>
                                                            </form></li>


                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!--cart for desktop start-->
                            <div class="header-cart for-desktop">
                                <a href="#" id="cartLink"
                                    onclick="if (parseInt(document.querySelector('.cartCount').textContent) > 0) { window.location.href = '/cart'; } else { alert('Your cart is empty!'); }">
                                    <i class="fa-light fa-cart-shopping"></i><span class="cartCount">0</span>
                                </a>
                            </div>
                            <!--cart for desktop end-->
                        </div>


                        <!--login and cart section end-->

                        <ul class="social-link">
                            <li><a href="{{ $setting->fbLink }}" title="Facebook" target="_blank"><i
                                        class="fa-brands fa-facebook-f"></i></a>
                            </li>
                            <li><a href="{{ $setting->twitterLink }}" title="Twitter" target="_blank"><i
                                        class="fa-brands fa-twitter"></i></a></li>
                            <li><a href="{{ $setting->instaLink }}" title="Instagram" target="_blank"><i
                                        class="fa-brands fa-instagram"></i></a>
                            </li>
                            <li><a href="{{ $setting->youtubeLink }}" title="YouTube" target="_blank"><i
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
                                <li><a href="/blog">Blogs</a></li>
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
                                                        name="search" placeholder="Type here to search...">

                                                    <ul id="suggestions" class="suggestions-list"></ul>
                                                </div>
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
                                    <!-- Footer Logo -->
                                    <img src="{{ url('/') }}/user/assets/images/logo-light.png" alt="image" class="footer-logo">
                                    
                                    <!-- Footer Description -->
                                    <p>
                                        @php
                                            $setting = \App\Models\Setting::first();
                                        @endphp
                                        {{ $setting->description ?? 'Effective gems is an online store targeting B2B Marketplace and the end customer...' }}
                                    </p>

                                    <ul class="as_contact_list">
                                        <!-- Phone -->
                                        <li>
                                            <img src="{{ url('/') }}/user/assets/images/svg/phone.svg" alt="">
                                            <p>
                                                <a href="tel:{{ $setting->phone1 ?? '+91 7328835585' }}">{{ $setting->phone1 ?? '+91 7328835585' }}</a>
                                            </p>
                                        </li>

                                        <!-- Email -->
                                        <li>
                                            <img src="{{ url('/') }}/user/assets/images/svg/mail.svg" alt="">
                                            <p>
                                                <a href="mailto:{{ $setting->email1 ?? 'gemseffective@gmail.com' }}">{{ $setting->email1 ?? 'gemseffective@gmail.com' }}</a>
                                            </p>
                                        </li>

                                        <!-- Address -->
                                        <li>
                                            <img src="{{ url('/') }}/user/assets/images/svg/map.svg" alt="">
                                            <a href="{{ $setting->address_link ?? 'https://goo.gl/maps/sPAPcT8hU8fhtetH7' }}" target="_blank">
                                                <p>{{ $setting->address ?? 'Plot No - A/88, Saheed Nagar<br />Bhubaneswar, Odisha - 751007' }}</p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- <div class="col-lg-2 col-md-2 col-sm-12 col-12">
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
                            </div> -->
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
                        <p>Copyright &copy; 2024. Punyatoya Enterprises. All Rights Reserved.
                        </p>

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
    <script src="{{ url('/') }}/user/assets/js/owl.carousel.min.js"></script>
    <script src="{{ url('/') }}/user/assets/js/easy-responsive-tabs.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <!-- Fotorama -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

    <script src="../cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

    <!-- banner owl start -->
    <script>
    $(".banner-owl").owlCarousel({
        autoplay: false,
        dots: false,
        nav: true,
        loop: true,
        margin: 15,
        smartSpeed: 1520,

        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 1,
            },
            900: {
                items: 1,
            }
        }
    });
    </script>
    <!-- banner owl end -->

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

    <script>
        // Check if there's an error message
        @if (Session::has('error'))
            toastr.error("{{ session('error') }}");
        @endif

        // Check if there's a success message
        @if (Session::has('message'))
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search");
            const suggestionsBox = document.getElementById("suggestions");

            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const suggestionsUrl = @json(route('api.suggestions'));

            searchInput.addEventListener("input", function() {
                const query = this.value.trim();

                if (query.length > 2) { // Trigger suggestions when input is more than 2 characters
                    fetch(`${suggestionsUrl}?query=${query}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken // Include the CSRF token here
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = ""; // Clear previous suggestions

                            if (data.length > 0) {
                                data.forEach(suggestion => {
                                    const li = document.createElement("li");

                                    // Create the anchor tag with the product details route
                                    const a = document.createElement("a");
                                    a.href =
                                        `{{ route('user.productdetails', ['prodid' => '__prodid__']) }}`
                                        .replace('__prodid__', suggestion.id);
                                    // Use the product's ID in the URL
                                    a.textContent = suggestion
                                        .productName; // Assuming 'productName' is the key for suggestions

                                    // Append the anchor to the list item
                                    li.appendChild(a);

                                    // Optionally, you can add a click event for setting the search input value
                                    li.addEventListener("click", function() {
                                        searchInput.value = suggestion.productName;
                                        suggestionsBox.innerHTML =
                                            ""; // Clear suggestions on selection
                                    });

                                    // Append the list item to the suggestions box
                                    suggestionsBox.appendChild(li);
                                });
                            }

                        })
                        .catch(error => console.error("Error fetching suggestions:", error));
                } else {
                    suggestionsBox.innerHTML = ""; // Clear suggestions if input is too short
                }
            });

            // Hide suggestions when clicking outside the input
            document.addEventListener("click", function(e) {
                if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.innerHTML = "";
                }
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.querySelector('.slider-content');
        const text = content.innerHTML;
        
        content.innerHTML = text + " &nbsp; " + text ;
    });
</script>
@stack('scripts')
</body>

</html>
