@extends('admin.layout')
@section('page-title', 'Edit-Profile')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Profile</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Update Profile</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="editProfileForm">
                            @csrf
                            <!-- <input type="hidden" name="_token" value="">
                            <input type="hidden" name="id" value="1"> -->
                            <!-- <div class="form-group row">
                                    <label class="col-form-label col-md-2">Profile Photo</label>
                                    <div class="col-md-6">
                                        <input class="form-control imgInp" name="profile_photo_path" type="file">
                                        <span style="color:red; font-style:italic;font-size:15px">Only JPG,png files are acceptable</span></br>
                                        <div class="profile_photo_path"><img src="assets/img/preview.jpg" alt="image" class="preview">
                                            <button type="button" class="btn btn-sm btn-danger">Remove</button>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <img id="profile_photo_path" class="preview" src="assets/img/preview.jpg" alt="image"/>
                                    </div>

                                </div> -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" id="name" value="{{ $adminData->name }}"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">Email Id</label>
                                <div class="col-md-12">
                                    <input type="email" name="email" id="email" value="{{ $adminData->email }}"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
        $(document).ready(function() {
            $('#editProfileForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.profile.update') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success('Profile updated successfully.');
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $('#nameError').text(errors.name ? errors.name[0] : '');
                        $('#emailError').text(errors.email ? errors.email[0] : '');
                    }
                });
            });
        });
    </script>

@endsection