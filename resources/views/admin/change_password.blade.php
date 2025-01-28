@extends('admin.layout')
@section('page-title', 'Change-Password')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Change Password</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="icofont icofont-home"></i>Dashboard</a></li>
                        <li class="breadcrumb-item"><a>Change Password</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="changePasswordForm">
                            @csrf
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control">
                                <span class="text-danger" id="currentPasswordError"></span>
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control">
                                <span class="text-danger" id="newPasswordError"></span>
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-control">
                                <span class="text-danger" id="newPasswordConfirmationError"></span>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Password</button>
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
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.updatePassword') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success('Password updated successfully.');
                    setTimeout(function() {
                        window.location.href =
                            "{{ route('admin.showChangePasswordForm') }}";
                    }, 1000);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $('#currentPasswordError').text(errors.current_password ? errors
                        .current_password[0] : '');
                    $('#newPasswordError').text(errors.new_password ? errors.new_password[
                        0] : '');
                    $('#newPasswordConfirmationError').text(errors
                        .new_password_confirmation ? errors.new_password_confirmation[
                            0] : '');
                }
            });
        });
    });
</script>

@endsection