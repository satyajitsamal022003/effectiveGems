@extends('admin.layout')
@section('page-title', 'Edit-Settings')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Setting</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Update Setting</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <form method="POST" action="{{ route('admin.storesettings') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#basictab1" data-toggle="tab">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#basictab2" data-toggle="tab">Home Page</a>
                                </li>
                            </ul>
                            <input type="hidden" name="id" value="{{ $setting->id ?? '' }}">

                            <div class="tab-content">
                                <div class="tab-pane show active" id="basictab1">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Phone <span class="text-danger">*</span></label>
                                                <input class="form-control" name="phone1" placeholder="Phone" value="{{ $setting->phone1 ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Phone-2</label>
                                                <input class="form-control" name="phone2" placeholder="Phone-2" value="{{ $setting->phone2 ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" name="email1" placeholder="Email" value="{{ $setting->email1 ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Email2</label>
                                                <input class="form-control" name="email2" placeholder="Email2" value="{{ $setting->email2 ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea class="form-control" name="address" placeholder="Address" cols="50" rows="4">{{ $setting->address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Opening Time</label>
                                                <input class="form-control" name="workingHour" placeholder="Opening Time" value="{{ $setting->workingHour ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Facebook</label>
                                                <input class="form-control" name="fbLink" placeholder="Facebook" value="{{ $setting->fbLink ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Twitter</label>
                                                <input class="form-control" name="twitterLink" placeholder="Twitter" value="{{ $setting->twitterLink ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Instagram</label>
                                                <input class="form-control" name="instaLink" placeholder="Instagram" value="{{ $setting->instaLink ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>YouTube</label>
                                                <input class="form-control" name="youtubeLink" placeholder="YouTube" value="{{ $setting->youtubeLink ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Home Page Tab -->
                                <div class="tab-pane" id="basictab2">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Heading 1 <span class="text-danger">*</span></label>
                                                <input class="form-control" name="heading1" placeholder="Heading 1" value="{{ $setting->heading1 ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Description 1</label>
                                                <textarea class="form-control ckeditor" name="description1" placeholder="Description 1" cols="50" rows="4">{{ $setting->description1 ?? '' }}</textarea>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-md-2">Image</label>
                                                <div class="col-md-8">
                                                    <input class="form-control imgInp" name="image" type="file" id="imageInput" accept="image/*">
                                                    <span style="color:red; font-style:italic; font-size:15px">Only JPG, PNG files are acceptable</span><br>
                                                    <div class="image">
                                                        <img id="imagePreview" class="preview"
                                                            src="{{ !empty($setting->image) ? asset('uploads/settings/' . $setting->image) : asset('assets/img/preview.jpg') }}"
                                                            alt="preview-img">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Button Link <span class="text-danger">*</span></label>
                                                <input class="form-control" name="button" placeholder="Button Link" value="{{ $setting->button ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Header Script</label>
                                                <textarea class="form-control" name="header_script" placeholder="Header Script" cols="50" rows="4">{{ $setting->header_script ?? '' }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Footer Script</label>
                                                <textarea class="form-control" name="footer_script" placeholder="Footer Script" cols="50" rows="4">{{ $setting->footer_script ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">
                                    <button type="submit" class="btn btn-primary">Update Setting</button>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById('imagePreview').src = reader.result;
        };
        if (event.target.files.length > 0) {
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>
@endsection