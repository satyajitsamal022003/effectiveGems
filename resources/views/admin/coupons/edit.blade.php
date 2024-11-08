@extends('admin.layout')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Coupons</h3>{{ $isDisabled }}
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">Coupons</a></li>
                            <li class="breadcrumb-item active">Edit Coupon</li>
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
                                    <a class="nav-link active" href="#coupon_tab" data-toggle="tab">Coupon</a>
                                </li>
                            </ul>

                            <form method="POST" action="{{ route('coupons.update', $coupon->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="coupon_tab">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <fieldset class="fieldset-style">
                                                    <div class="row">
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <label>Coupon Name</label>
                                                                <input class="form-control required" {{ $isDisabled }}
                                                                    placeholder="Coupon Name" name="name" required
                                                                    value="{{ old('name', $coupon->name) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Coupon Code</label>
                                                                <input class="form-control required" {{ $isDisabled }}
                                                                    placeholder="Coupon Code" name="code" required
                                                                    value="{{ old('code', $coupon->code) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea class="form-control" {{ $isDisabled }} placeholder="Description" name="description">{{ old('description', $coupon->description) }}</textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Type</label>
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                {{ $isDisabled }} name="type"
                                                                                value="1"
                                                                                {{ old('type', $coupon->type) == '1' ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Flat</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                {{ $isDisabled }} name="type"
                                                                                value="2"
                                                                                {{ old('type', $coupon->type) == '2' ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label">Percentage</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Value</label>
                                                                        <input class="form-control" placeholder="Value"
                                                                            name="value" {{ $isDisabled }}
                                                                            value="{{ old('value', $coupon->value) }}">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <!-- Checkboxes for products, categories, wholeSite, and subCategories -->
                                                            <div class="form-group">
                                                                <label>Applicable To:</label>
                                                                <div class="form-check">
                                                                    <input {{ $isDisabled }} class="form-check-input"
                                                                        type="checkbox" name="wholeSite" id="wholeSite"
                                                                        {{ $coupon->wholeSite ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="wholeSite">Whole
                                                                        Site</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input {{ $isDisabled }} class="form-check-input"
                                                                        type="checkbox" name="products" id="products"
                                                                        {{ $coupon->products ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="products">Products</label>
                                                                </div>

                                                                <!-- Product variant dropdown -->
                                                                <div id="variant-container" class="variant-container"
                                                                    style="{{ $coupon->products ? 'display: block;' : 'display: none;' }}">
                                                                    <div class="form-group">
                                                                        <label for="">Search Product</label>
                                                                        <select class="form-control" {{ $isDisabled }}
                                                                            id="productList" name="productList[]" multiple>
                                                                            <option value="">--Select Product--
                                                                            </option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}"
                                                                                    {{ in_array($product->id, $coupon->productList) ? 'selected' : '' }}>
                                                                                    {{ $product->productName }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        {{ $isDisabled }} name="categories"
                                                                        id="categories"
                                                                        {{ $coupon->categories ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="categories">Categories</label>
                                                                </div>
                                                                <div id="categories-container" class="variant-container"
                                                                    style="{{ $coupon->categories ? 'display: block;' : 'display: none;' }}">
                                                                    <div class="form-group">
                                                                        <label for="">Search Categories</label>
                                                                        <select {{ $isDisabled }} class="form-control"
                                                                            id="categoriesList" name="categoriesList[]"
                                                                            multiple>
                                                                            <option value="">--Select Category--
                                                                            </option>
                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    {{ in_array($category->id, $coupon->categoriesList) ? 'selected' : '' }}>
                                                                                    {{ $category->categoryName }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        {{ $isDisabled }} name="subCategories"
                                                                        id="subCategories"
                                                                        {{ $coupon->subCategories ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="subCategories">Sub Categories</label>
                                                                </div>
                                                                <div id="subCategories-container"
                                                                    class="variant-container"
                                                                    style="{{ $coupon->subCategories ? 'display: block;' : 'display: none;' }}">
                                                                    <div class="form-group">
                                                                        <label for="">Search Sub Categories</label>
                                                                        <select {{ $isDisabled }} class="form-control"
                                                                            id="subCategoriesList"
                                                                            name="subCategoriesList[]" multiple>
                                                                            <option value="">--Select Sub Category--
                                                                            </option>
                                                                            @foreach ($subCategories as $subCategory)
                                                                                <option value="{{ $subCategory->id }}"
                                                                                    {{ in_array($subCategory->id, $coupon->subCategoriesList) ? 'selected' : '' }}>
                                                                                    {{ $subCategory->subCategoryName }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Date fields -->
                                                            <div class="form-group">
                                                                <label for="startDate">Start Date</label>
                                                                <input type="date" {{ $isDisabled }}
                                                                    class="form-control" name="startDate" id="startDate"
                                                                    required
                                                                    value="{{ old('startDate', \Carbon\Carbon::parse($coupon->startDate)->format('Y-m-d')) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="endDate">End Date</label>
                                                                <input type="date" {{ $isDisabled }}
                                                                    class="form-control" name="endDate" id="endDate"
                                                                    required
                                                                    value="{{ old('endDate', \Carbon\Carbon::parse($coupon->endDate)->format('Y-m-d')) }}">
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Status</label>
                                                                <select class="form-control required" name="status"
                                                                    required>
                                                                    <option value="">--Select Status--</option>
                                                                    <option value="1"
                                                                        {{ $coupon->status == 1 ? 'selected' : '' }}>Active
                                                                    </option>
                                                                    <option value="0"
                                                                        {{ $coupon->status == 0 ? 'selected' : '' }}>
                                                                        Inactive</option>
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
                                                    id="nextButton">Update</button>
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
        const initialiseSelect2 = (id) => {
            $(`#${id}`).select2({
                placeholder: '--Select--',
                allowClear: true
            });
        }

        $('#products').on('change', function() {
            if ($(this).is(':checked')) {
                $('#variant-container').slideDown();
                initialiseSelect2('productList');
            } else {
                $('#variant-container').slideUp();
            }
        });
        $('#categories').on('change', function() {
            if ($(this).is(':checked')) {
                $('#categories-container').slideDown();
                initialiseSelect2('categoriesList');
            } else {
                $('#categories-container').slideUp();
            }
        });
        $('#subCategories').on('change', function() {
            if ($(this).is(':checked')) {
                $('#subCategories-container').slideDown();
                initialiseSelect2('subCategoriesList');
            } else {
                $('#subCategories-container').slideUp();
            }
        });

        // Call on load
        if ($('#products').is(':checked')) {
            $('#variant-container').show();
            initialiseSelect2('productList');
        }
        if ($('#categories').is(':checked')) {
            $('#categories-container').show();
            initialiseSelect2('categoriesList');
        }
        if ($('#subCategories').is(':checked')) {
            $('#subCategories-container').show();
            initialiseSelect2('subCategoriesList');
        }
    </script>
@endsection
