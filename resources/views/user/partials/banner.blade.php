<!--banner slider start-->
<section class="banner-slider">
    <div class="banner-carousel">
        @foreach($banners as $banner)
        @if($banner->status)
        <div class="banner-slide">
            <div class="banner-image">
                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}">
                <div class="banner-content">
                    @if($banner->title)
                    <h2 class="banner-title" data-aos="fade-up" data-aos-delay="100">{{ $banner->title }}</h2>
                    @endif
                    @if($banner->description)
                    <p class="banner-description" data-aos="fade-up" data-aos-delay="200">{{ $banner->description }}</p>
                    @endif
                    @if($banner->button_text && $banner->button_link)
                    <a href="{{ $banner->button_link }}" class="as_btn" data-aos="fade-up" data-aos-delay="300">
                        {{ $banner->button_text }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>
<section class="as_banner_wrapper" style="background-image: url('/user/assets/images/banner.jpg');">
    <div class="container">
        <div class="row as_verticle_center">
            <div class="col-lg-6">
                <div class="as_banner_detail">
                    <h1 data-aos="fade-right">Buy High Quality Natural Gemstones At Wholesale Prices. B2B
                        Marketplace</h1><a href="#" class="as_btn" data-aos="zoom-in">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!--banner slider end-->

@push('scripts')
<script>
    $(document).ready(function() {
        $('.banner-carousel').slick({
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: true,
            responsive: [{
                breakpoint: 768,
                settings: {
                    arrows: false
                }
            }]
        });
    });
</script>

<style>
    .banner-slider {
        position: relative;
        overflow: hidden;
    }

    .banner-slide {
        position: relative;
    }

    .banner-image {
        position: relative;
        width: 100%;
        height: 600px;
    }

    .banner-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #fff;
        max-width: 800px;
        width: 90%;
        padding: 20px;
    }

    .banner-title {
        font-size: 48px;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .banner-description {
        font-size: 18px;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 768px) {
        .banner-image {
            height: 400px;
        }

        .banner-title {
            font-size: 32px;
        }

        .banner-description {
            font-size: 16px;
        }
    }
</style>
@endpush