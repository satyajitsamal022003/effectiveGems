@extends('admin.layout')
@section('page-title', $category->categoryName ?? '') 
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Edit Category</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.listcat') }}">Categories</a></li>
                        <li class="breadcrumb-item active">{{$category->categoryName ?? ''}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.updatecat', $category->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST') <!-- This method is for updating the record -->

                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control" name="categoryName" value="{{ $category->categoryName }}" required>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">Image</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="image" type="file" accept="image/*" onchange="previewImage(event, 'imagePreview')">
                                    <span style="color:red; font-style:italic;font-size:15px">Only JPG, PNG files are acceptable</span>
                                </div>
                                <div class="col-md-2">
                                    <img id="imagePreview" src="{{ asset($category->image) }}" alt="Category Image" style="max-width: 100px;">
                                </div>
                            </div>

                            <!-- Banner Upload -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">Banner</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="banner" type="file" accept="image/*" onchange="previewImage(event, 'bannerPreview')">
                                    <span style="color:red; font-style:italic;font-size:15px">Only JPG, PNG files are acceptable</span>
                                </div>
                                <div class="col-md-2">
                                    <img id="bannerPreview" src="{{ asset($category->banner) }}" alt="Category Banner" style="max-width: 100px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" class="form-control" name="sortOrder" value="{{ $category->sortOrder }}">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description">{{ $category->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <h5>Category Status</h5>
                                <div class="onoffswitch">
                                    <input type="checkbox" name="status" class="onoffswitch-checkbox" id="statusSwitch" {{ $category->status ? 'checked' : '' }}>
                                    <label class="onoffswitch-label" for="statusSwitch"></label>
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
                                                placeholder="Meta Title" name="metaTitle"
                                                value="">
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label">Meta Description
                                            </label>

                                            <textarea id="metaDescription" class="form-control " placeholder="First Description" name="metaDescription"
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


                                <!--image seo start-->
                                <div class="main-box seo-box mt-3">
                                    <div class="inline-text">
                                        <p>Search Engine Image Preview</p>
                                        <a href="javascript:" onclick="showseoimgedit()">Edit
                                            Image SEO</a>
                                    </div>
                                    <div class="seo-img-edit-box">
                                        <div class="form-group">
                                            <label>Alternative Text</label>
                                            <input class="form-control" id="imageAlt"
                                                placeholder="Alternative Text" name="imageAlt"
                                                value="">
                                        </div>

                                        <div class="form-group">
                                            <label>Image Title</label>
                                            <input class="form-control" id="imageTitle"
                                                placeholder="Image Title" name="imageTitle"
                                                value="">
                                        </div>

                                        <div class="form-group">
                                            <label>Image Caption</label>
                                            <input class="form-control" id="imageCaption"
                                                placeholder="Image Caption" name="imageCaption"
                                                value="">
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label">Image Description
                                            </label>
                                            <textarea id="imageDesc" class="form-control" placeholder="Description" name="imageDesc" cols="50"
                                                rows="4"></textarea>

                                        </div>

                                    </div>
                                </div>
                                <script>
                                    function showseoimgedit() {
                                        $(".seo-img-edit-box").toggle('slow');
                                    }
                                </script>
                                <button type="button" class="btn btn-success mt-3">Generate
                                    SEO </button>
                                <!--image seo en-->
                            </div>

                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
</script>

<script>
    function previewImage(event, previewId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewId);
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection