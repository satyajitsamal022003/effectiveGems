@extends('admin.layout')
@section('page-title', 'Coupon-Add')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Coupons</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('admin.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="/admin/coupons">Coupons</a></li>
                            <li class="breadcrumb-item active"> Add Coupon</li>
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
                                    <a class="nav-link active" href="#product_tab" data-toggle="tab">Coupon</a>
                                </li>
                            </ul>

                            <form method="POST" action="{{ route('coupons.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="product_tab">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <fieldset class="fieldset-style">
                                                    <div class="row">
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <label>Coupon Name</label>
                                                                <input class="form-control required"
                                                                    placeholder="Coupon Name" name="name" required
                                                                    value="{{ old('name') }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Coupon Code</label>
                                                                <input class="form-control required"
                                                                    placeholder="Coupon Code" name="code" required
                                                                    value="{{ old('code') }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea class="form-control" placeholder="Description" name="description">{{ old('description') }}</textarea>
                                                            </div>
                                                            <div class="row">
                                                            <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Type</label>

                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                name="type" value="1"
                                                                                {{ old('type') == '1' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="type">Flat</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                name="type" value="2"
                                                                                {{ old('type') == '2' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="type">Percentage</label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-6">

                                                                    <div class="form-group">
                                                                        <label>Value</label>
                                                                        <input class="form-control" placeholder="value"
                                                                            name="value">{{ old('value') }}</input>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Checkboxes for products, categories, wholeSite, and subCategories -->
                                                            <div class="form-group">
                                                                <label>Applicable To:</label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="wholeSite" id="wholeSite">
                                                                    <label class="form-check-label" for="wholeSite">Whole
                                                                        Site</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="products" id="products">
                                                                    <label class="form-check-label"
                                                                        for="products">Products</label>
                                                                </div>

                                                                <!-- Product variant dropdown -->
                                                                <div id="variant-container" class="variant-container"
                                                                    style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="">Search Product</label>
                                                                        <select class="form-control" id="productList"
                                                                            name="productList[]" multiple>
                                                                            <option value="">--Select Product--
                                                                            </option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}">
                                                                                    {{ $product->productName }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" id="minQuantityField" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label>Quantity</label>
                                                                        <input type="number" min="1" class="form-control" placeholder="Quantity"
                                                                            name="min_quantity">{{ old('min_quantity') }}</input>
                                                                    </div>
                                                                    </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="categories" id="categories">
                                                                    <label class="form-check-label"
                                                                        for="categories">Categories</label>
                                                                </div>
                                                                <div id="categories-container" class="variant-container"
                                                                    style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="">Search Categories</label>
                                                                        <select class="form-control" id="categoriesList"
                                                                            name="categoriesList[]" multiple>
                                                                            <option value="">--Select Product--
                                                                            </option>
                                                                            @foreach ($categories as $product)
                                                                                <option value="{{ $product->id }}">
                                                                                    {{ $product->categoryName }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="subCategories" id="subCategories">
                                                                    <label class="form-check-label"
                                                                        for="subCategories">Sub
                                                                        Categories</label>
                                                                </div>
                                                                <div id="subCategories-container"
                                                                    class="variant-container" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="">Search Sub Categories</label>
                                                                        <select class="form-control"
                                                                            id="subCategoriesList"
                                                                            name="subCategoriesList[]" multiple>
                                                                            <option value="">--Select Product--
                                                                            </option>
                                                                            @foreach ($subCategories as $product)
                                                                                <option value="{{ $product->id }}">
                                                                                    {{ $product->subCategoryName }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="is_combo" id="is_combo" value="1">
                                                                    <label class="form-check-label"
                                                                        for="is_combo">Is Combo</label>
                                                                </div>

                                                            </div>

                                                            <!-- Date fields -->
                                                            <div class="form-group">
                                                                <label for="startDate">Start Date</label>
                                                                <input type="date" class="form-control"
                                                                    name="startDate" id="startDate" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="endDate">End Date</label>
                                                                <input type="date" class="form-control" name="endDate"
                                                                    id="endDate" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Status</label>
                                                                <select class="form-control required" name="status"
                                                                    required>
                                                                    <option value="">--Select Status--</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div> <!-- end col -->
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4">
                                                <button class="btn btn-primary" type="submit"
                                                    id="nextButton">Create</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        const intialiseSelect2 = (id) => {
            $(`#${id}`).select2({
                placeholder: '--Select--',
                allowClear: true
            });
        }
    </script>
    <script>
        $('#products').on('change', function() {
            if ($(this).is(':checked')) {
                $('#variant-container').slideDown();
                intialiseSelect2('productList');
            } else {
                $('#variant-container').slideUp();
            }
        });
        $('#products').on('change', function () {
            if ($(this).is(':checked')) {
                $('#minQuantityField').slideDown(); // or .show()
            } else {
                $('#minQuantityField').slideUp(); // or .hide()
            }
        });
        $('#categories').on('change', function() {
            if ($(this).is(':checked')) {
                $('#categories-container').slideDown();
                intialiseSelect2('categoriesList');
            } else {
                $('#categories-container').slideUp();
            }
        });
        $('#subCategories').on('change', function() {
            if ($(this).is(':checked')) {
                $('#subCategories-container').slideDown();
                intialiseSelect2('subCategoriesList');
            } else {
                $('#subCategories-container').slideUp();
            }
        });
    </script>
    <script>
            document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('input[name="wholeSite"], input[name="products"], input[name="categories"], input[name="subCategories"]');

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                if (this.checked) {
                    checkboxes.forEach((cb) => {
                        if (cb !== this) {
                            cb.checked = false;
                        }
                    });

                    // Hide all containers
                    document.getElementById('variant-container').style.display = 'none';
                    document.getElementById('categories-container').style.display = 'none';
                    document.getElementById('subCategories-container').style.display = 'none';

                    // Show the selected container
                    if (this.id === 'products') {
                        document.getElementById('variant-container').style.display = 'block';
                        intialiseSelect2('productList');
                    } else if (this.id === 'categories') {
                        document.getElementById('categories-container').style.display = 'block';
                        intialiseSelect2('categoriesList');
                    } else if (this.id === 'subCategories') {
                        document.getElementById('subCategories-container').style.display = 'block';
                        intialiseSelect2('subCategoriesList');
                    }
                }
            });
        });
    });
    </script>
@endsection
