@extends('user.layout')
@section('content')
@php
    $pageName="home";
    $metaTitle=$current_page->metaTitle;
    $metaKeyword=$current_page->metaKeyword;
    $metaDescription=$current_page->metaDescription;
    $metaImage=$current_page->image;
@endphp

<section class="container">
    <div class="as_breadcrum_wrapper"
         >
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>{{$current_page->pageName}}</h1>
                <ul class="breadcrumb">
                    <li><a href="{{route('user.index')}}">Home</a></li>
                    <li>{{$current_page->pageName}}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="pages-bg">
            <div class="row">
                <!--@if(!empty($current_page['image']))-->
                <!--    <div class="col-lg-6 col-md-6">-->
                <!--        <div class="as_aboutimg text-right" data-aos="fade-right">-->
                <!--            <img src="{{$current_page['image']}}" alt="" class="img-responsive">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--@endif-->
                @if(!empty($current_page['image']))
                    <div class="col-lg-12 col-md-12"> @else
                            <div class="col-lg-12 col-md-12"> @endif
                                <div class="as_about_detail" data-aos="fade-left">
                                    <h1 class="as_heading mb-3">{{$current_page['pageName']}}</h1>
                                    {!!$current_page->description!!}
                                </div>
                            </div>
                    </div>
            </div>
        </div>
    </div>
</section>
@endsection
