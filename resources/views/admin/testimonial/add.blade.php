@extends('admin.layout')
@section('page-title', 'Testimonial-Add')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Testimonials</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.listtestimonial') }}">Testimonials</a></li>
                            <li class="breadcrumb-item active">Add Testimonial</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.storetestimonial') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="basictab">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <fieldset class="fieldset-style">
                                                    <div class="row">
                                                        <div class="col-xl-8">

                                                            <!-- Testimonial Name -->
                                                            <div class="form-group">
                                                                <label>Author Name</label>
                                                                <input class="form-control" type="text" placeholder="Author Name" name="authorName" value="">
                                                            </div>

                                                            <!-- Designation -->
                                                            <div class="form-group">
                                                                <label>Designation</label>
                                                                <input class="form-control" type="text" placeholder="Designation" name="designation" value="">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Heading</label>
                                                                <input class="form-control" type="text" placeholder="Heading" name="heading" value="">
                                                            </div>

                                                            <!-- Image Upload -->
                                                            <div class="form-group row">
                                                                <label class="col-form-label col-md-2">Image</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control imgInp" name="image" id="imageUpload" type="file" accept="image/*">
                                                                    <span style="color:red; font-style:italic;font-size:15px">Only JPG, PNG files are acceptable</span><br>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <img id="imagePreview" class="preview" src="" alt="No image selected" style="max-width: 100px;">
                                                                </div>
                                                            </div>

                                                            <!-- Testimonial Text -->
                                                            <div class="form-group">
                                                                <label>Testimonial Text</label>
                                                                <textarea class="form-control" name="testimonialText" rows="4"></textarea>
                                                            </div>

                                                            <!-- Testimonial Status -->
                                                            <div class="form-group">
                                                                <h5>Testimonial Status Off/On</h5>
                                                                <div class="onoffswitch">
                                                                    <input type="checkbox" name="status" class="onoffswitch-checkbox" id="testimonialStatus" tabindex="0">
                                                                    <label class="onoffswitch-label" for="testimonialStatus"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">
                                        <button type="submit" class="btn btn-primary">Add Testimonial</button>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('testimonialText');

        // Image Preview
        document.getElementById('imageUpload').onchange = function(evt) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(evt.target.files[0]);
        };
    </script>
@endsection
