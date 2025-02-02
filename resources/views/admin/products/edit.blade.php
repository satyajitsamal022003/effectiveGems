@extends('admin.layout')
@section('page-title', $product->productName ?? '')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Product</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{route('admin.dashboard')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('admin.listproduct')}}">Product</a></li>
                        <li class="breadcrumb-item active">{{ $product->productName ?? '' }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#product_tab" data-toggle="tab">Product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#product_image_tab" data-toggle="tab">Product Image</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#product_description_tab" data-toggle="tab">Product
                                    Description</a>
                            </li>
                        </ul>

                        <form method="POST" action="{{ route('admin.editproductdata', $product->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane show active" id="product_tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <fieldset class="fieldset-style">
                                                <div class="row">
                                                    <div class="col-xl-8">
                                                        <div class="form-group">
                                                            <label>Product Name </label>
                                                            <input class="form-control required" id=""
                                                                placeholder="Product Name" name="productName" required
                                                                value="{{ $product->productName }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Product Small Name (To show on the variant)</label>
                                                            <input class="form-control" id=""
                                                                placeholder="Product Small Name" name="variantName"
                                                                value="{{ $product->variantName }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Category </label>
                                                            <select class="form-control required" id="categoryId"
                                                                name="categoryId" onchange="getSubCategory(this.value)"
                                                                required>
                                                                <option value="">--Select Category--</option>
                                                                @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $category->id == $product->categoryId ? 'selected' : '' }}>
                                                                    {{ $category->categoryName }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Sub Category (Optional) </label>
                                                            <input type="hidden" id="subCategoryData"
                                                                value="{{ $product->subCategoryId }}">
                                                            <select class="form-control" id="subCategory"
                                                                name="subCategoryId">
                                                                <option value="">--Select Sub Category--</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><strong>Sort Order</strong></label>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <label for="sortOrderAllProduct">Sort Order All Product</label>
                                                                    <input type="number" class="form-control" id="sortOrderAllProduct" placeholder="Sort Order All Product" name="sortOrder" value="{{ $product->sortOrder }}" min="0">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="sortOrderCategory">Sort Order Category</label>
                                                                    <input type="number" class="form-control" id="sortOrderCategory" placeholder="Sort Order Category" name="sortOrderCategory" value="{{ $product->sortOrderCategory }}" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-lg-6">
                                                                    <label for="sortOrderSubCategory">Sort Order Sub Category</label>
                                                                    <input type="number" class="form-control" id="sortOrderSubCategory" placeholder="Sort Order Sub Category" name="sortOrderSubCategory" value="{{ $product->sortOrderSubCategory  }}" min="0">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="sortOrderPopular">Sort Order Popular</label>
                                                                    <input type="number" class="form-control" id="sortOrderPopular" placeholder="Sort Order Popular" name="sortOrderPopular" value="{{ $product->sortOrderPopular }}" min="0">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label for="price_type">Price Per <span
                                                                        class="text-danger">*</span> </label>
                                                                <select class="form-control required" id="price_type"
                                                                    name="price_type" required>
                                                                    <option value="">--Select Price Per--
                                                                    </option>
                                                                    <option value="Gram"
                                                                        {{ 'Gram' == $product->price_type ? 'selected' : '' }}>
                                                                        Gram</option>
                                                                    <option value="Kg"
                                                                        {{ 'Kg' == $product->price_type ? 'selected' : '' }}>
                                                                        Kg</option>
                                                                    <option value="Ratti"
                                                                        {{ 'Ratti' == $product->price_type ? 'selected' : '' }}>
                                                                        Ratti</option>
                                                                    <option value="Carat"
                                                                        {{ 'Carat' == $product->price_type ? 'selected' : '' }}>
                                                                        Carat</option>
                                                                    <option value="Pcs"
                                                                        {{ 'Pcs' == $product->price_type ? 'selected' : '' }}>
                                                                        Pcs</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-4">
                                                                <div class="form-group">
                                                                    <label>Price MRP</label>
                                                                    <input class="form-control"
                                                                        placeholder="Price MRP" name="priceMRP"
                                                                        value="{{ $product->priceMRP }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4">
                                                                <div class="form-group">
                                                                    <label>Price B2C</label>
                                                                    <input class="form-control required"
                                                                        placeholder="Price B2C" name="priceB2C"
                                                                        required value="{{ $product->priceB2C }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4">
                                                                <div class="form-group">
                                                                    <label>Price B2B</label>
                                                                    <input class="form-control"
                                                                        placeholder="Price B2B" name="priceB2B"
                                                                        value="{{ $product->priceB2B }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <label for="lower-product-range">Lower product range
                                                                    <i class="fa fa-info-circle"
                                                                        data-bs-toggle="tooltip"
                                                                        title="Enter the Higher product range First"></i>
                                                                </label>
                                                                <input type="number" class="form-control"
                                                                    id="min_product_qty" name="min_product_qty"
                                                                    value="{{ $product->min_product_qty }}"
                                                                    oninput="validateProductRange()">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label>Higher product range</label>
                                                                <input type="number" class="form-control"
                                                                    id="max_product_qty" name="max_product_qty"
                                                                    value="{{ $product->max_product_qty }}"
                                                                    oninput="validateProductRange()">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-xl-12">
                                                                <label for="out_of_stock" class="flex items-center">
                                                                    <input id="out_of_stock" type="checkbox"
                                                                        class="form-checkbox" name="out_of_stock"
                                                                        value="1"
                                                                        {{ $product->out_of_stock == 1 ? 'checked' : '' }}>
                                                                    <span class="ml-2 text-sm text-gray-600">Product
                                                                        Out Of Stock</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Certification</label>
                                                            <select class="form-control" id=""
                                                                name="certificationId">
                                                                <option value="">--Select--</option>
                                                                @foreach (App\Models\Certification::where('status', 1)->get() as $certification)
                                                                <option value="{{ $certification->id }}"
                                                                    {{ $certification->id == $product->certificationId ? 'selected' : '' }}>
                                                                    {{ $certification->amount }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Activation</label>
                                                            <select class="form-control" name="activationId">
                                                                <option value="">--Select--</option>
                                                                @foreach (App\Models\Activations::where('status', 1)->get() as $activation)
                                                                <option value="{{ $activation->id }}"
                                                                    {{ $activation->id == $product->activationId ? 'selected' : '' }}>
                                                                    {{ $activation->amount }}
                                                                </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Courier Type</label>
                                                            <select class="form-control" id=""
                                                                name="courierTypeId">
                                                                <option value="">--Select--</option>
                                                                @foreach (App\Models\Couriertype::where('status', 1)->get() as $courier)
                                                                <option value="{{ $courier->id }}"
                                                                    {{ $courier->id == $product->courierTypeId ? 'selected' : '' }}>
                                                                    {{ $courier->courier_name }}
                                                                    {{ $courier->courier_price != 0 ? ' - ' . $courier->courier_price : '' }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <div class="row">
                                                                <div class="col-xl-4">
                                                                    <label for="add_variant" class="flex items-center">
                                                                        <!-- Hidden input to send 'is_variant' as 0 when unchecked -->
                                                                        <input type="hidden" name="is_variant" value="0">
                                                                        <!-- Set the checkbox checked state based on the current value of is_variant -->
                                                                        <input id="add_variant" type="checkbox" class="form-checkbox" name="is_variant" value="1" {{ old('is_variant', $product->is_variant) == 1 ? 'checked' : '' }}>
                                                                        <span class="ml-2 text-sm text-gray-600">Have Variant?</span>
                                                                    </label>
                                                                </div>

                                                                    {{-- <div class="col-xl-4">
                                                                            <button type="button" class="bg-info btn text-white" id="add-variant-btn"><i class="fa-regular fa-plus"></i> Add Variant</button>
                                                                        </div> --}}
                                                                </div>
                                                                <div id="variant-container" class="variant-container">
                                                                    <div class="form-group">
                                                                        <label for="">Search Product For
                                                                            Variant</label>
                                                                        <select class="form-control"
                                                                            id="productvariant" name="variant[]"
                                                                            multiple>
                                                                            @php
                                                                            $selectedVariants = json_decode(
                                                                            $product->variant,
                                                                            true,
                                                                            );
                                                                            @endphp
                                                                            @foreach ($productsData as $productData)
                                                                            <option value="{{ $productData->id }}"
                                                                                {{ is_array($selectedVariants) && in_array($productData->id, $selectedVariants) ? 'selected' : '' }}>
                                                                                {{ $productData->productName }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="main-box seo-box">
                                                            <div class="inline-text">
                                                                <p>Search Engine Listing Preview</p>
                                                                <a href="javascript:" onclick="showseoedit()">Edit
                                                                    Website SEO</a>
                                                            </div>
                                                            <div class="box-content">
                                                                <p id="seo_title"></p>
                                                                <a id="seo_url" href=""></a>
                                                                <span id="seo_description"></span>
                                                            </div>
                                                            <div class="seo-edit-box">
                                                                <div class="form-group">
                                                                    <label>Meta Title </label>
                                                                    <input class="form-control" id="metaTitle"
                                                                        placeholder="Meta Title" name="metaTitle" value="{{ $product->metaTitle }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Meta Description
                                                                    </label>
                                                                    <textarea id="metaDescription" class="form-control" placeholder="First Description" name="metaDescription"
                                                                        cols="50" rows="4">{{ $product->metaDescription }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Seo Url </label>
                                                                    <input class="form-control" id="seoUrl"
                                                                        placeholder="Seo Url" name="seoUrl"
                                                                        value="{{ $product->seoUrl }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Meta Keyword </label>
                                                                    <input class="form-control" id="metaKeyword"
                                                                        placeholder="Meta Keyword" name="metaKeyword"
                                                                        value="{{ $product->metaKeyword }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Meta image </label>
                                                                    <input class="form-control" id="metaImage"
                                                                        placeholder="Meta image" name="metaImage"
                                                                        value="{{ $product->metaImage }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function showseoedit() {
                                                                $(".seo-edit-box").toggle('slow');
                                                            }
                                                        </script>
                                                        <button type="button" onclick="showseoedit()" class="btn btn-success mt-3">Generate
                                                            SEO
                                                        </button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div> <!-- end col -->
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">
                                            <button class="btn btn-primary" type="button"
                                                id="nextButton">Update</button>
                                        </label>
                                    </div>
                                </div>
                                <div class="tab-pane" id="product_image_tab">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-2">Icon </label>
                                                <div class="col-md-8">
                                                    <input class="form-control imgInp" name="icon" id="icon"
                                                        type="file" accept="image/*">
                                                    <span style="color:red; font-style:italic;font-size:15px">Only
                                                        JPG,png files are acceptable</span><br>
                                                </div>
                                                <div class="col-md-2">
                                                    <img id="icondata" class="preview"
                                                        src="{{ asset($product->icon) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-2">Image1</label>
                                                <div class="col-md-8">
                                                    <input class="form-control imgInp" name="image1" id="image1"
                                                        type="file" accept="image/*">
                                                    <span style="color:red; font-style:italic;font-size:15px">Only
                                                        JPG,png files are acceptable</span><br>
                                                </div>
                                                <div class="col-md-2">
                                                    <img id="image1data" class="preview"
                                                        src="{{ asset($product->image1) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-2">Image2</label>
                                                <div class="col-md-8">
                                                    <input class="form-control imgInp" name="image2" id="image2"
                                                        type="file" accept="image/*">
                                                    <span style="color:red; font-style:italic;font-size:15px">Only
                                                        JPG,png files are acceptable</span><br>
                                                </div>
                                                <div class="col-md-2">
                                                    <img id="image2data" class="preview"
                                                        src="{{ asset($product->image2) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-2">Image3 </label>
                                                <div class="col-md-8">
                                                    <input class="form-control imgInp" name="image3" id="image3"
                                                        type="file">
                                                    <span style="color:red; font-style:italic;font-size:15px">Only
                                                        JPG,png files are acceptable</span><br>
                                                </div>
                                                <div class="col-md-2">
                                                    <img id="image3data" class="preview"
                                                        src="{{ asset($product->image3) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <!--image seo start-->
                                                <div class="main-box seo-box mt-3">
                                                    <div class="inline-text">
                                                        <p>Search Engine Image Preview</p>
                                                        <a href="javascript:" onclick="showseoimgedit1()">Edit Image
                                                            SEO</a>
                                                    </div>
                                                    <div class="seo-img-edit-box1">
                                                        <div class="form-group">
                                                            <label>Alternative Text</label>
                                                            <input class="form-control" id="imageAlt1"
                                                                placeholder="Alternative Text" name="imageAlt1"
                                                                value="{{$product->imageAlt1}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Image Title</label>
                                                            <input class="form-control" id="imageTitle1"
                                                                placeholder="Image Title" name="imageTitle1"
                                                                value="{{$product->imageTitle1}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Image Caption</label>
                                                            <input class="form-control" id="imageCaption1"
                                                                placeholder="Image Caption" name="imageCaption1"
                                                                value="{{$product->imageCaption1}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-form-label">Image Description </label>
                                                            <textarea id="imageDesc1" class="form-control" placeholder="Description" name="imageDesc1" cols="50"
                                                                rows="4">{{$product->imageDesc1}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    function showseoimgedit1() {
                                                        $(".seo-img-edit-box1").toggle('slow');
                                                    }
                                                </script>
                                                <button type="button" class="btn btn-success mt-3">Save</button>
                                            </div>
                                            <div class="mb-2">
                                                <div class="main-box seo-box mt-3">
                                                    <div class="inline-text">
                                                        <p>Search Engine Image Preview</p>
                                                        <a href="javascript:" onclick="showseoimgedit2()">Edit Image
                                                            SEO</a>
                                                    </div>
                                                    <div class="seo-img-edit-box2">
                                                        <div class="form-group">
                                                            <label>Alternative Text</label>
                                                            <input class="form-control" id="imageAlt2"
                                                                placeholder="Alternative Text" name="imageAlt2"
                                                                value="{{$product->imageAlt2}}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Image Title</label>
                                                            <input class="form-control" id="imageTitle2"
                                                                placeholder="Image Title" name="imageTitle2"
                                                                value="{{$product->imageTitle2}}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Image Caption</label>
                                                            <input class="form-control" id="imageCaption2"
                                                                placeholder="Image Caption" name="imageCaption2"
                                                                value="{{$product->imageCaption2}}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-form-label">Image Description </label>
                                                            <textarea id="imageDesc2" class="form-control" placeholder="Description" name="imageDesc2" cols="50"
                                                                rows="4">{{$product->imageDesc2}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    function showseoimgedit2() {
                                                        $(".seo-img-edit-box2").toggle('slow');
                                                    }
                                                </script>
                                                <button type="button" class="btn btn-success mt-3">Save</button>
                                                <!--image seo en-->
                                            </div>
                                            <div class="mb-2">
                                                <!--image seo start-->
                                                <div class="main-box seo-box mt-3">
                                                    <div class="inline-text">
                                                        <p>Search Engine Image Preview</p>
                                                        <a href="javascript:" onclick="showseoimgedit3()">Edit Image
                                                            SEO</a>
                                                    </div>
                                                    <div class="seo-img-edit-box3">
                                                        <div class="form-group">
                                                            <label>Alternative Text</label>
                                                            <input class="form-control" id="imageAlt3"
                                                                placeholder="Alternative Text" name="imageAlt3"
                                                                value="{{$product->imageAlt3}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Image Title</label>
                                                            <input class="form-control" id="imageTitle3"
                                                                placeholder="Image Title" name="imageTitle3"
                                                                value="{{$product->imageTitle3}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Image Caption</label>
                                                            <input class="form-control" id="imageCaption3"
                                                                placeholder="Image Caption" name="imageCaption3"
                                                                value="{{$product->imageCaption3}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-form-label">Image Description </label>
                                                            <textarea id="imageDesc3" class="form-control" placeholder="Description" name="imageDesc3" cols="50"
                                                                rows="4">{{$product->imageDesc3}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    function showseoimgedit3() {
                                                        $(".seo-img-edit-box3").toggle('slow');
                                                    }
                                                </script>
                                                <button type="button" class="btn btn-success mt-3">Save</button>
                                                <!--image seo en-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">
                                            <a href="#product_tab" data-toggle="tab"><button
                                                    class="btn btn-info">Previous</button></a>
                                            <button class="btn btn-primary" type="button"
                                                id="nextButton2">Update</button>
                                        </label>
                                    </div>
                                </div>
                                <!--Description tab start-->
                                <div class="tab-pane" id="product_description_tab">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">

                                                <div class="form-group">
                                                    <label>Description 1</label>
                                                    <textarea id="description1" name="productDesc1" class="form-control" placeholder="Description 1">{{ $product->productDesc1 }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Heading 2(Description)</label>
                                                    <input class="form-control" placeholder="Heading 2"
                                                        name="productHeading2"
                                                        value="{{ $product->productHeading2 }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description 2</label>
                                                    <textarea id="description2" name="productDesc2" class="form-control" placeholder="Description 2">{{ $product->productDesc2 }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Heading 3(Shipping Policy)</label>
                                                    <input class="form-control" placeholder="Heading 3"
                                                        name="productHeading3"
                                                        value="{{ $product->productHeading3 }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description 3</label>
                                                    <textarea id="description3" name="productDesc3" class="form-control" placeholder="Description 3">{{ $product->productDesc3 }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Heading 4(Return Policy)</label>
                                                    <input class="form-control" placeholder="Heading 4"
                                                        name="productHeading4"
                                                        value="{{ $product->productHeading4 }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description 4</label>
                                                    <textarea id="description4" name="productDesc4" class="form-control" placeholder="Description 4">{{ $product->productDesc4 }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Heading 5(Payment Method)</label>
                                                    <input class="form-control" placeholder="Heading 5"
                                                        name="productHeading5"
                                                        value="{{ $product->productHeading5 }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description 5</label>
                                                    <textarea id="description5" name="productDesc5" class="form-control" placeholder="Description 5">{{ $product->productDesc5 }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">
                                            <a href="#product_image_tab" data-toggle="tab"><button
                                                    class="btn btn-info">Previous</button></a>
                                            <button type="submit" class="btn btn-primary"> Update Product </button>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('description1');
    CKEDITOR.replace('description2');
    CKEDITOR.replace('description3');
    CKEDITOR.replace('description4');
    CKEDITOR.replace('description5');

    $(document).ready(function() {
        $('#productvariant').select2({
            placeholder: '--Select--',
            allowClear: true
        });
    });

    $(document).ready(function() {
        // $('#add-variant-btn').on('click', function() {
        //     const newVariant = `
        //         <div class="form-group">
        //             <label for="">Search Product For Variant</label>
        //             <div class="form-group d-flex align-items-center">
        //                 <input class="form-control" placeholder="Search Product and Select" name="product_variant[]">
        //                 <button type="button" class="btn text-white btn-sm bg-danger remove-variant-btn" style="margin-left: 5px;"> <i class="fa-regular fa-trash-can"></i></button>
        //             </div>
        //         </div>
        //     `;
        //     $('#variant-container').append(newVariant);
        // });

        // // Delegate the click event to remove buttons
        // $('#variant-container').on('click', '.remove-variant-btn', function() {
        //     $(this).closest('.variant-item').remove();
        // });

        $('#add_variant').on('change', function() {
            if ($(this).is(':checked')) {
                $('.variant-container').slideDown();
            } else {
                $('.variant-container').slideUp();
            }
        });

        function getSubCategory(categoryId) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.getSubCategory') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'categoryId': categoryId
                },
                success: function(response) {
                    $('#subCategory').empty();
                    $('#subCategory').append("<option value=''>--Select Sub Category--</option>");
                    var selectedSubCategoryId = $("#subCategoryData").val();
                    $.each(response.data, function(key, val) {
                        $('#subCategory').append("<option value='" + val.id + "' " + (val
                                .id == selectedSubCategoryId ? 'selected' : '') + ">" +
                            val.subCategoryName + "</option>");
                    });
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }

        function validateProductRange() {
            var minQty = parseInt(document.getElementById('min_product_qty').value, 10);
            var maxQty = parseInt(document.getElementById('max_product_qty').value, 10);

            if (!isNaN(minQty) && !isNaN(maxQty)) {
                if (minQty >= maxQty) {
                    alert('The lower product range must be less than the higher product range.');
                    document.getElementById('min_product_qty').value = '';
                }
            }
        }

        document.getElementById('icon').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('icondata').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };

        document.getElementById('image1').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image1data').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };

        document.getElementById('image2').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image2data').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };

        document.getElementById('image3').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image3data').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };

        getSubCategory($('#categoryId').val());

        window.getSubCategory = getSubCategory;
        window.validateProductRange = validateProductRange;
    });
    const updateProductPartly = () => {
        // Show loading indicator
        $('#loading-indicator').show();
        
        const formData = new FormData();
        let hasErrors = false;

        // Get all form elements in the product tab
        const $form = $('#product_tab');
        
        // Handle regular form fields
        $form.find('input, select, textarea').each(function() {
            const $field = $(this);
            const name = $field.attr('name');
            
            if (!name) return;

            // Handle different input types
            if ($field.attr('type') === 'checkbox') {
                formData.append(name, $field.is(':checked') ? '1' : '0');
            } else if ($field.attr('type') === 'file') {
                const files = $field[0].files;
                if (files.length > 0) {
                    formData.append(name, files[0]);
                }
            } else if ($field.is('select')) {
                const value = $field.val();
                if (value !== null && value !== undefined && value !== '') {
                    formData.append(name, value);
                }
            } else {
                const value = $field.val();
                if (value !== null && value !== undefined) {
                    formData.append(name, value);
                }
            }

            // Log the field data for debugging
            console.log(`Field: ${name}, Value: ${$field.val()}, Type: ${$field.attr('type') || $field.prop('tagName')}`);

            // Validate required fields
            if ($field.hasClass('required')) {
                const value = $field.val();
                if (!value || (typeof value === 'string' && value.trim() === '') || value === '--Select--') {
                    $field.addClass('is-invalid');
                    hasErrors = true;
                    console.log(`Validation failed for: ${name}`);
                } else {
                    $field.removeClass('is-invalid');
                }
            }
        });

        // Handle variant data specially
        const variantSelect = $('#productvariant');
        if (variantSelect.length) {
            formData.append('variant', JSON.stringify(variantSelect.val()));
        }

        // Add CSRF token
        formData.append('_token', '{{ csrf_token() }}');

        if (hasErrors) {
            toastr.error('Please fill all required fields');
            $('#loading-indicator').hide();
            return false;
        }
        // Make the AJAX request
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateProductPartly', $product->id) }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Product information updated successfully');
                        resolve(true);
                    } else {
                        toastr.error(response.message || 'Failed to update product');
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                    console.error(xhr.responseText);
                    resolve(false);
                },
                complete: function() {
                    $('#loading-indicator').hide();
                }
            });
        });
    }
    const updateProductImages = () => {
        $('#loading-indicator').show();
        const formData = new FormData();
        let hasErrors = false;

        // Handle image files and their SEO fields
        const imageFields = ['icon', 'image1', 'image2', 'image3'];
        const maxFileSize = 2 * 1024 * 1024; // 2MB

        imageFields.forEach(field => {
            const fileInput = $(`input[name="${field}"]`)[0];
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                
                // Validate file size
                if (file.size > maxFileSize) {
                    toastr.error(`${field}: File size must be less than 2MB`);
                    hasErrors = true;
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    toastr.error(`${field}: Please upload an image file`);
                    hasErrors = true;
                    return;
                }

                formData.append(field, file);
            }
        });

        // Add image SEO fields
        $('#product_image_tab').find('input[type="text"], textarea').each(function() {
            const field = $(this);
            const name = field.attr('name');
            if (name && name.match(/^(imageAlt|imageTitle|imageCaption|imageDesc)/)) {
                formData.append(name, field.val());
            }
        });

        formData.append('_token', '{{ csrf_token() }}');

        if (hasErrors) {
            $('#loading-indicator').hide();
            return false;
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateProductPartly', $product->id) }}",
                data: formData,
                contentType: false,
                processData: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = (evt.loaded / evt.total) * 100;
                            $('#upload-progress').width(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Product images updated successfully');
                        resolve(true);
                    } else {
                        toastr.error(response.message || 'Failed to update images');
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                    console.error(xhr.responseText);
                    resolve(false);
                },
                complete: function() {
                    $('#loading-indicator').hide();
                    $('#upload-progress').width('0%');
                }
            });
        });
    }
    const updateProductDescription = () => {
        $('#loading-indicator').show();
        const formData = new FormData();
        let hasErrors = false;

        // Update CKEditor instances before collecting data
        for (let i = 1; i <= 5; i++) {
            const editor = CKEDITOR.instances[`description${i}`];
            if (editor) {
                editor.updateElement();
            }
        }

        // Collect all description fields
        $('#product_description_tab').find('input, textarea').each(function() {
            const $field = $(this);
            const name = $field.attr('name');
            if (!name) return;

            const value = $field.val();
            if (value !== null && value !== undefined) {
                formData.append(name, value);
            }

            if ($field.hasClass('required') && !value) {
                $field.addClass('is-invalid');
                hasErrors = true;
            } else {
                $field.removeClass('is-invalid');
            }
        });

        formData.append('_token', '{{ csrf_token() }}');

        if (hasErrors) {
            toastr.error('Please fill all required fields');
            $('#loading-indicator').hide();
            return false;
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateProductPartly', $product->id) }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Product description updated successfully');
                        resolve(true);
                    } else {
                        toastr.error(response.message || 'Failed to update description');
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                    console.error(xhr.responseText);
                    resolve(false);
                },
                complete: function() {
                    $('#loading-indicator').hide();
                }
            });
        });
    }

    // Add loading and error indicators
    $('body').append(`
        <div id="loading-indicator" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center;">
                <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="text-light mt-2" id="loading-text">Processing...</div>
                <div class="text-light mt-2" id="error-text" style="display:none; color: #ff6b6b;"></div>
            </div>
        </div>
    `);

    // Add error display
    $('.card-body').prepend(`
        <div id="form-errors" class="alert alert-danger" style="display:none;">
            <h5>Please correct the following errors:</h5>
            <ul></ul>
        </div>
    `);
    
    // Add upload progress bar
    $('#product_image_tab').prepend(`
        <div class="mb-3">
            <div class="progress">
                <div id="upload-progress" class="progress-bar" role="progressbar" style="width: 0%">
                    <span class="progress-text">0%</span>
                </div>
            </div>
            <small class="text-muted mt-1" id="upload-status"></small>
        </div>
    `);

    // Function to update loading text
    function updateLoadingText(text) {
        $('#loading-text').text(text);
    }

    // Function to update progress
    function updateProgress(percent) {
        $('#upload-progress').width(percent + '%');
        $('.progress-text').text(percent + '%');
        $('#upload-status').text('Uploading files... ' + percent + '% complete');
    }

    // Function to show validation errors
    function showValidationErrors(errors) {
        const $formErrors = $('#form-errors');
        const $errorList = $formErrors.find('ul');
        $errorList.empty();
        
        Object.keys(errors).forEach(field => {
            const $field = $(`[name="${field}"]`);
            if ($field.length) {
                $field.addClass('is-invalid');
                $field.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                $errorList.append(`<li>${errors[field][0]}</li>`);
            }
        });
        
        $formErrors.show();
        
        // Scroll to errors
        $('html, body').animate({
            scrollTop: $formErrors.offset().top - 100
        }, 500);
    }

    // Function to show error message
    function showError(message) {
        $('#error-text').text(message).show();
        setTimeout(() => {
            $('#error-text').hide();
        }, 5000);
    }

    // Function to clear validation errors
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }

    // Step 1: Product Info
    document.getElementById('nextButton').addEventListener('click', async function() {
        clearValidationErrors();
        updateLoadingText('Saving product information...');
        try {
            const success = await updateProductPartly();
            if (success) {
                // var nextTabLink = document.querySelector('a[href="#product_image_tab"]');
                // var nextTab = new bootstrap.Tab(nextTabLink);
                // nextTab.show();
            }
        } catch (error) {
            console.error('Error in step 1:', error);
            if (error.responseJSON && error.responseJSON.errors) {
                showValidationErrors(error.responseJSON.errors);
            }
            toastr.error('Failed to update product information. Please check the form for errors.');
        }
    });

    // Step 2: Images
    document.getElementById('nextButton2').addEventListener('click', async function() {
        clearValidationErrors();
        updateLoadingText('Uploading images...');
        try {
            const success = await updateProductImages();
            if (success) {
                // var nextTabLink = document.querySelector('a[href="#product_description_tab"]');
                // var nextTab = new bootstrap.Tab(nextTabLink);
                // nextTab.show();
            }
        } catch (error) {
            console.error('Error in step 2:', error);
            if (error.responseJSON && error.responseJSON.errors) {
                showValidationErrors(error.responseJSON.errors);
            }
            toastr.error('Failed to update product images. Please check the files and try again.');
        }
    });

    // Step 3: Description
    $('form').on('submit', async function(e) {
        e.preventDefault();
        clearValidationErrors();
        updateLoadingText('Saving product description...');
        try {
            const success = await updateProductDescription();
            if (success) {
                toastr.success('Product updated successfully! Redirecting...');
                setTimeout(() => {
                    window.location.href = "{{ route('admin.listproduct') }}";
                }, 1000);
            }
        } catch (error) {
            console.error('Error in step 3:', error);
            if (error.responseJSON && error.responseJSON.errors) {
                showValidationErrors(error.responseJSON.errors);
            }
            toastr.error('Failed to update product description. Please check the form for errors.');
        }
    });

    // Handle tab navigation
    $('.nav-tabs a').on('click', function(e) {
        // Prevent tab change if current step is not complete
        const currentTab = $('.tab-pane.active').attr('id');
        const targetTab = $(this).attr('href').substring(1);
        
        // if (currentTab === 'product_tab' && targetTab !== 'product_tab') {
        //     e.preventDefault();
        //     $('#nextButton').click();
        // } else if (currentTab === 'product_image_tab' && targetTab === 'product_description_tab') {
        //     e.preventDefault();
        //     $('#nextButton2').click();
        // }
    });

    // Prevent accidental form submission
    $(document).ready(function() {
        $('form').on('keypress', function(e) {
            if (e.keyCode === 13) { // Enter key
                e.preventDefault();
                return false;
            }
        });
    });

    // Handle tab changes
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        // Save current tab state
        localStorage.setItem('lastProductTab', $(e.target).attr('href'));
    });

    // Restore last active tab
    var lastTab = localStorage.getItem('lastProductTab');
    if (lastTab) {
        $('a[href="' + lastTab + '"]').tab('show');
    }
</script>
@endsection
