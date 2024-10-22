@extends('admin.layout')
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
                                    href="https://effectivegems.com/admin_panel/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Product</a></li>
                            <li class="breadcrumb-item active"> Edit Product</li>
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
                                                                <label>Sort Order</label>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control" id=""
                                                                            placeholder="Sort Order All Product"
                                                                            name="sortOrder"
                                                                            value="{{ $product->sortOrder }}">
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control" id=""
                                                                            placeholder="Sort Order Category"
                                                                            name="sortOrderSubCategory"
                                                                            value="{{ $product->sortOrderSubCategory }}">
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control" id=""
                                                                            placeholder="Sort Order Sub Category"
                                                                            name="sortOrderCategory"
                                                                            value="{{ $product->sortOrderCategory }}">
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control" id=""
                                                                            placeholder="Sort Order Popular"
                                                                            name="sortOrderPopular"
                                                                            value="{{ $product->sortOrderPopular }}">
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
                                                                            {{ $certification->amount }}</option>
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
                                                                            {{ $activation->amount }}</option>
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
                                                                            <label for="add_variant"
                                                                                class="flex items-center">
                                                                                <input id="add_variant" type="checkbox"
                                                                                    class="form-checkbox"
                                                                                    name="is_variant" checked
                                                                                    value="{{ $product->is_variant }}">
                                                                                <span
                                                                                    class="ml-2 text-sm text-gray-600">have
                                                                                    Variant?</span>
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
                                                                            placeholder="Meta Title" name="metaTitle">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-form-label">Meta Description
                                                                        </label>
                                                                        <textarea id="metaDescription" class="form-control" placeholder="First Description" name="metaDescription"
                                                                            cols="50" rows="4"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Seo Url </label>
                                                                        <input class="form-control" id="seoUrl"
                                                                            placeholder="Seo Url" name="seoUrl"
                                                                            value="">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Meta Keyword </label>
                                                                        <input class="form-control" id="metaKeyword"
                                                                            placeholder="Meta Keyword" name="metaKeyword"
                                                                            value="">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Meta image </label>
                                                                        <input class="form-control" id="metaImage"
                                                                            placeholder="Meta image" name="metaImage"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                function showseoedit() {
                                                                    $(".seo-edit-box").toggle('slow');
                                                                }
                                                            </script>
                                                            <button type="button" class="btn btn-success mt-3">Generate
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
                                                            <a href="javascript:" onclick="showseoimgedit()">Edit Image
                                                                SEO</a>
                                                        </div>
                                                        <div class="seo-img-edit-box">
                                                            <div class="form-group">
                                                                <label>Alternative Text</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Alternative Text" name=""
                                                                    value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Image Title</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Image Title" name=""
                                                                    value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Image Caption</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Image Caption" name=""
                                                                    value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label">Image Description </label>
                                                                <textarea id="" class="form-control" placeholder="Description" name="" cols="50"
                                                                    rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function showseoimgedit() {
                                                            $(".seo-img-edit-box").toggle('slow');
                                                        }
                                                    </script>
                                                    <button type="button" class="btn btn-success mt-3">Save</button>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="main-box seo-box mt-3">
                                                        <div class="inline-text">
                                                            <p>Search Engine Image Preview</p>
                                                            <a href="javascript:" onclick="showseoimgedit()">Edit Image
                                                                SEO</a>
                                                        </div>
                                                        <div class="seo-img-edit-box">
                                                            <div class="form-group">
                                                                <label>Alternative Text</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Alternative Text" name=""
                                                                    value="">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Image Title</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Image Title" name=""
                                                                    value="">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Image Caption</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Image Caption" name=""
                                                                    value="">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-form-label">Image Description </label>
                                                                <textarea id="" class="form-control" placeholder="Description" name="" cols="50"
                                                                    rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function showseoimgedit() {
                                                            $(".seo-img-edit-box").toggle('slow');
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
                                                            <a href="javascript:" onclick="showseoimgedit()">Edit Image
                                                                SEO</a>
                                                        </div>
                                                        <div class="seo-img-edit-box">
                                                            <div class="form-group">
                                                                <label>Alternative Text</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Alternative Text" name=""
                                                                    value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Image Title</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Image Title" name=""
                                                                    value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Image Caption</label>
                                                                <input class="form-control" id=""
                                                                    placeholder="Image Caption" name=""
                                                                    value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label">Image Description </label>
                                                                <textarea id="" class="form-control" placeholder="Description" name="" cols="50"
                                                                    rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function showseoimgedit() {
                                                            $(".seo-img-edit-box").toggle('slow');
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
            // Create a FormData object
            const formData = new FormData();

            // Append form values
            formData.append('productName', $('input[name="productName"]').val());
            formData.append('variantName', $('input[name="variantName"]').val());
            formData.append('categoryId', $('input[name="categoryId"]').val());
            formData.append('subCategoryId', $('input[name="subCategoryId"]').val());
            formData.append('sortOrder', $('input[name="sortOrder"]').val());
            formData.append('sortOrderSubCategory', $('input[name="sortOrderSubCategory"]').val());
            formData.append('sortOrderCategory', $('input[name="sortOrderCategory"]').val());
            formData.append('sortOrderPopular', $('input[name="sortOrderPopular"]').val());
            formData.append('price_type', $('input[name="price_type"]').val());
            formData.append('priceMRP', $('input[name="priceMRP"]').val());
            formData.append('priceB2C', $('input[name="priceB2C"]').val());
            formData.append('min_product_qty', $('input[name="min_product_qty"]').val());
            formData.append('max_product_qty', $('input[name="max_product_qty"]').val());
            formData.append('out_of_stock', $('input[name="out_of_stock"]').val());
            formData.append('certificationId', $('input[name="certificationId"]').val());
            formData.append('activationId', $('input[name="activationId"]').val());
            formData.append('courierTypeId', $('input[name="courierTypeId"]').val());
            formData.append('is_variant', $('input[name="is_variant"]').val());
            formData.append('variant', $('input[name="variant"]').val());
            formData.append('metaTitle', $('input[name="metaTitle"]').val());
            formData.append('metaDescription', $('input[name="metaDescription"]').val());
            formData.append('seoUrl', $('input[name="seoUrl"]').val());
            formData.append('metaKeyword', $('input[name="metaKeyword"]').val());

            // Add CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            // Make the AJAX request
            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateProductPartly', $product->id) }}",
                data: formData,
                contentType: false, // Important for file uploads
                processData: false, // Prevent jQuery from converting the data into a query string
                success: function(response) {
                    return true;
                    toastr.success('Updated');

                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }
    </script>

    <script>
        document.getElementById('nextButton').addEventListener('click', function() {
            // Get only the required input and select elements
            var requiredFields = document.querySelectorAll('#product_tab .required');
            var allValid = true;

            // Loop through only the required fields and check for validation
            requiredFields.forEach(function(field) {
                if (field.tagName === 'SELECT') {
                    // Check if the select value is the default option
                    if (field.value === '--Select--') {
                        field.style.borderColor = 'red'; // Highlight empty required select field
                        allValid = false;
                    } else {
                        field.style.borderColor = ''; // Reset field style
                    }
                } else {
                    // Check for input fields
                    if (field.value === '') {
                        field.style.borderColor = 'red'; // Highlight empty required field
                        allValid = false;
                    } else {
                        field.style.borderColor = ''; // Reset field style
                    }
                }
            });

            // If all required fields are valid, proceed to the next tab

            if (allValid) {
                if (updateProductPartly()) {
                    var nextTabLink = document.querySelector('a[href="#product_image_tab"]');
                    var nextTab = new bootstrap.Tab(nextTabLink);
                    nextTab.show();
                }
            } else {
                alert('Please fill all required fields.');
            }
        });
    </script>
    <script>
        document.getElementById('nextButton2').addEventListener('click', function() {
            // Get only the required input elements in the product image tab
            var requiredFields = document.querySelectorAll('#product_image_tab .required');
            var allValid = true;

            // Loop through the required fields and check for validation
            requiredFields.forEach(function(field) {
                if (!field.value) {
                    field.style.borderColor = 'red'; // Highlight empty required field
                    allValid = false;
                } else {
                    field.style.borderColor = ''; // Reset field style
                }
            });

            // If all required fields are valid, proceed to the next tab
            if (allValid) {
                var nextTabLink = document.querySelector('a[href="#product_description_tab"]');
                var nextTab = new bootstrap.Tab(nextTabLink);
                nextTab.show();
            } else {
                alert('Please fill all required fields.');
            }
        });
    </script>
@endsection
