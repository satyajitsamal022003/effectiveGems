@extends('admin.layout')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Setting</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Setting</a></li>
                        <li class="breadcrumb-item active"> Update Setting</li>
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
                                    <a class="nav-link active" href="#basictab1" data-toggle="tab">General</a></li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#basictab2" data-toggle="tab">Home Page</a></li>
                            </ul>
                            <input type="hidden" name="id" value="1">

                            <div class="tab-content">
                                <div class="tab-pane show active" id="basictab1">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <fieldset class="fieldset-style">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <!-- Phone fields -->
                                                        <div class="form-group">
                                                            <label>Phone <span class="text-danger">*</span></label>
                                                            <input class="form-control" id="phone1" name="phone1" placeholder="Phone" value="" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone-2</label>
                                                            <input class="form-control" id="phone2" name="phone2" placeholder="Phone-2" value="">
                                                        </div>
                                                        
                                                        <!-- Email fields -->
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input class="form-control" id="email1" name="email1" placeholder="Email" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email2</label>
                                                            <input class="form-control" id="email2" name="email2" placeholder="Email2" value="">
                                                        </div>
                                                        
                                                        <!-- Address field -->
                                                        <div class="form-group">
                                                            <label class="col-form-label">Address</label>
                                                            <textarea id="address" class="form-control" name="address" placeholder="Address" cols="50" rows="4"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <!-- Social media and other info fields -->
                                                        <div class="form-group">
                                                            <label>Opening Time</label>
                                                            <input class="form-control" name="opening_time" placeholder="Opening Time" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Facebook</label>
                                                            <input class="form-control" name="facebook" placeholder="Facebook" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Twitter</label>
                                                            <input class="form-control" name="twitter" placeholder="Twitter" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Instagram</label>
                                                            <input class="form-control" name="instagram" placeholder="Instagram" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>YouTube</label>
                                                            <input class="form-control" name="youtube" placeholder="YouTube" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <!-- Home Page Tab -->
                                <div class="tab-pane" id="basictab2">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <!-- Heading and description fields -->
                                            <div class="form-group">
                                                <label>Heading 1 <span class="text-danger">*</span></label>
                                                <input class="form-control" id="heading1" name="heading1" placeholder="Heading 1" value="" >
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Description 1</label>
                                                <textarea id="description1" class="form-control ckeditor" name="description1" placeholder="Description 1" cols="50" rows="4"></textarea>
                                            </div>
                                            
                                            <!-- Image upload fields -->
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-2">Image</label>
                                                <div class="col-md-8">
                                                    <input class="form-control imgInp" name="image" type="file">
                                                    <span style="color:red; font-style:italic; font-size:15px">Only JPG, PNG files are acceptable</span><br>
                                                    <div class="image"><img src="assets/img/preview.jpg" alt="preview-img" class="preview">
                                                        <button type="button" class="btn btn-sm btn-danger">Remove</button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <img id="image" class="preview" src="assets/img/preview.jpg" alt="image"/>
                                                </div>
                                            </div>

                                            <!-- Other fields for content customization -->
                                            <div class="form-group">
                                                <label>Button Link <span class="text-danger">*</span></label>
                                                <input class="form-control" id="button" name="button" placeholder="Button Link" value="" >
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <!-- Script fields -->
                                            <div class="form-group">
                                                <label class="col-form-label">Header Script</label>
                                                <textarea id="header_script" class="form-control" name="header_script" placeholder="Header Script" cols="50" rows="4">{{$setting->header_script}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Footer Script</label>
                                                <textarea id="footer_script" class="form-control" name="footer_script" placeholder="Footer Script" cols="50" rows="4">{{$setting->footer_script}}</textarea>
                                            </div>
                                        </div>
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
        </form>
    </div>
</div>
@endsection
