@extends('user.layout')
@section('content')
@section('title', 'Testimonials | Effective Gems')
@section('description', 'Read what our happy clients have to say about Effective Gems.')
@section('image', asset('user/assets/images/testimonials-banner.jpg'))

<section class="container">
    <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-2.jpg');">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Testimonials</h1>
                <ul class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>Testimonials</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="as_customer_wrapper as_padderBottom40 as_padderTop40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="inline-header" data-aos="zoom-in" data-aos-duration="1500">
                    <h1 class="as_heading">What Our Clients Say</h1>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            @foreach ($testimonials as $testimonial)
                @if ($testimonial->status == 1)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="as_customer_content" data-aos="flip-left" data-aos-duration="1500">
                            <div class="flex_text">
                                <img src="{{ $testimonial->userImage ? asset($testimonial->userImage) : url('/user/assets/images/profile_user.jpg') }}" alt="image">
                                <h3>{{ $testimonial->userName }} - <span>{{ $testimonial->designation }}</span></h3>
                            </div>
                            <p>{!! $testimonial->description !!}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="pagination-bottom text-center mt-4" data-aos="fade-up">
            {{ $testimonials->links() }}
        </div>
    </div>
</section>

@endsection
