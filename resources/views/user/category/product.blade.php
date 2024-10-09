@extends('user.layout')
@section('content')
    <section class="container">
        <div class="as_breadcrum_wrapper" style="background-image: url('/user/assets/images/breadcrum-img-1.jpg');">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Category</h1>
                    <ul class="breadcrumb">
                        <li><a href="{{route('user.index')}}">Home</a></li>
                        <li>{{ $category->categoryName }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="as_blog_wrapper as_padderBottom40 as_padderTop40">
        <div class="container">

            @if ($pageNo == 1)
                <div class="sub-category-grid">
                    @foreach ($subcategories as $subcat)
                        <div class="sub-item">
                            <a href="/sub-category/{{ $subcat->id }}">
                                <img src="{{ asset($subcat->image) }}" alt="{{ $subcat->subCategoryName }}">
                            </a>
                            <div class="sub-text">
                                <h4>{{ $subcat->subCategoryName }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


            <!-- Pagination for Subcategories -->


            <!--<div class="row">-->
            <!--    <div class="col-lg-12 col-md-12 text-center">-->
            <!--        <div class="inline-header aos-init aos-animate" data-aos="zoom-in" data-aos-duration="1500">-->

            <!--            <div class="filter justify-content-end">-->
            <!--                <form action="" method="get">-->
            <!--                    <div class="select-filter">-->
            <!--                        <select name="orderBy" class="form-control form-select">-->
            <!--                            <option value="">Sort By</option>-->
            <!--                            <option value="categoryName">A-Z</option>-->
            <!--                            <option value="categoryName_Desc">Z-A</option>-->
            <!--                        </select>-->
            <!--                    </div>-->
            <!--                </form>-->
            <!--            </div>-->
            <!--        </div>                   -->
            <!--    </div>-->
            <!--</div>-->

            <div class="row mt-4">
                <div class="col-lg-12 col-md-12 text-center">
                    <!--<div class="inline-header aos-init aos-animate" data-aos="zoom-in" data-aos-duration="1500">-->
                    <!--    <div class="main-text">-->
                    <!--        <h1 class="as_heading">{{ $category->categoryName }} (Sub Category Products)</h1>-->
                    <!--    </div>-->
                    <!--</div>-->
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
           <div class="pagination-bottom" data-aos="fade-up">
                <nav>
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($subcategoryproducts->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $subcategoryproducts->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif
            
                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $subcategoryproducts->currentPage();
                            $lastPage = $subcategoryproducts->lastPage();
                            $showDots = false;
                        @endphp
            
                        {{-- Show first few pages --}}
                        @for ($i = 1; $i <= min(4, $lastPage); $i++)
                            @if ($i == $currentPage)
                                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $subcategoryproducts->url($i) }}">{{ $i }}</a></li>
                            @endif
                        @endfor
            
                        {{-- Show dots if current page is greater than 5 --}}
                        @if ($currentPage > 5)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
            
                        {{-- Show previous and current pages --}}
                        @for ($i = max(5, $currentPage - 2); $i <= min($currentPage + 2, $lastPage - 1); $i++)
                            @if ($i == $currentPage)
                                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $subcategoryproducts->url($i) }}">{{ $i }}</a></li>
                            @endif
                        @endfor
            
                        {{-- Show dots if current page is less than last page - 3 --}}
                        @if ($currentPage < $lastPage - 3)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
            
                        {{-- Show last few pages --}}
                        @for ($i = max($lastPage - 2, 1); $i <= $lastPage; $i++)
                            @if ($i == $currentPage)
                                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $subcategoryproducts->url($i) }}">{{ $i }}</a></li>
                            @endif
                        @endfor
            
                        {{-- Next Page Link --}}
                        @if ($subcategoryproducts->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $subcategoryproducts->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>






        </div>
    </section>
@endsection
