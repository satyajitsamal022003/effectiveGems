@extends('user.layout')
@section('content')
@section('title', 'Faqs | Effective Gems')
@section('description', 'Read Our Faqs.')
@section('image', asset('user/assets/images/testimonials-banner.jpg'))

<section class="container">
    <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-2.jpg');">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Faqs</h1>
                <ul class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>Faqs</li> 
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="as_blog_wrapper as_padderBottom40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="as_widget as_faq_widget">
                    <div class="accordion as_accordion" id="accordionPanelsStayOpenExample" data-aos="fade-up" data-aos-duration="1500">
                        <div class="row">
                            @foreach ($faqs->chunk(ceil($faqs->count() / 2)) as $faqChunk)
                                <div class="col-lg-6 col-xs-12">
                                    @foreach ($faqChunk as $faq)
                                        @if ($faq->is_active == 1)
                                            <div class="accordion-item">
                                                <div class="accordion-header" id="heading{{ $faq->id }}">
                                                    <h2 class="mb-0">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                                                            {{ $faq->question }}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faq->id }}">
                                                    <div class="accordion-body">
                                                        <p>{!! $faq->answer !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pagination-bottom text-center mt-4" data-aos="fade-up">
            {{ $faqs->links() }}
        </div>
    </div>
</section>
@endsection