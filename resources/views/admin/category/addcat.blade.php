@extends('admin.layout')
@section('page-title', 'Category-Add') 
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Category</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('admin.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('admin.listcat')}}">Category</a></li>
                            <li class="breadcrumb-item active"> Add Category</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.storecat') }}" enctype="multipart/form-data">
                                @csrf
                                {{-- <input type="hidden" name="_token" value=""> --}}
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="basictab">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <fieldset class="fieldset-style">
                                                    <div class="row">
                                                        <div class="col-xl-8">

                                                            <div class="form-group">
                                                                <label>Category Name </label>
                                                                <input class="form-control" type="text"
                                                                    placeholder="Category Name" name="categoryName"
                                                                    value="">
                                                            </div>

                                                            <!-- Image Upload -->
                                                            <div class="form-group row">
                                                                <label class="col-form-label col-md-2">Image</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control imgInp" name="image"
                                                                        id="imageUpload" type="file" accept="image/*">
                                                                    <span
                                                                        style="color:red; font-style:italic;font-size:15px">Only
                                                                        JPG, PNG files are acceptable</span><br>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <img id="imagePreview" class="preview" src=""
                                                                        alt="No image selected" style="max-width: 100px;">
                                                                </div>
                                                            </div>

                                                            <!-- Banner Upload -->
                                                            <div class="form-group row">
                                                                <label class="col-form-label col-md-2">Banner</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control imgInp" name="banner"
                                                                        id="bannerUpload" type="file" accept="image/*">
                                                                    <span
                                                                        style="color:red; font-style:italic;font-size:15px">Only
                                                                        JPG, PNG files are acceptable</span><br>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <img id="bannerPreview" class="preview" src=""
                                                                        alt="No banner selected" style="max-width: 100px;">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Sort Order </label>
                                                                <input class="form-control" placeholder="Sort Order"
                                                                    name="sortOrder" value="">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Description </label>
                                                                <textarea class="form-control" name="description"></textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <h5>Category Status Off/On</h5>
                                                                <div class="onoffswitch">
                                                                    <input type="checkbox" name="onoffswitch928"
                                                                        class="onoffswitch-checkbox"
                                                                        id="featured_productmyonoffswitch928"
                                                                        tabindex="0">
                                                                    <label class="onoffswitch-label"
                                                                        for="featured_productmyonoffswitch928"></label>
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

                                                    </div>
                                                </fieldset>
                                            </div> <!-- end col -->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">
                                        <button type="submit" class="btn btn-primary">Add Category</button>
                                    </label>
                                </div>
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
        // Image Preview
        document.getElementById('imageUpload').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };
    
        // Banner Preview
        document.getElementById('bannerUpload').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('bannerPreview').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };
    </script>
@endsection

