@extends('user.layout')
@section('content')
    @include('eusers.partials.header')
    <section class="container mb-5">
        <div class="account-body">
            <div class="profile-info">
                <div class="profile-img">
                    <form id="changePasswordForm">
                        @csrf
                        <div class="form-group col-lg-12 col-12">
                            <label for="old_password">Enter Old Password</label>
                            <input type="password" class="form-control" id="old_password" name="old_password">
                            <div class="text-danger" id="old_password_error"></div>
                        </div>
                        <div class="form-group col-lg-12 col-12">
                            <label for="new_password">Enter New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <div class="text-danger" id="new_password_error"></div>
                        </div>
                        <div class="form-group col-lg-12 col-12">
                            <label for="confirm_password">Re-Enter New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            <div class="text-danger" id="confirm_password_error"></div>
                        </div>
                        <div class="inline-edit-btn main-btn">
                            <button type="submit" class="as_btn">Save</button>
                            <button type="reset" class="as_btn cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="profile-img profile-edit-section">
                    <form id="updateProfileForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <h4 class="text-black">Edit Profile</h4>
                            <div class="form-group col-lg-12 col-12">
                                <div class="file-input-container">
                                    <input type="file" name="profile_img" class="sm-input-file" id="file" />
                                    <label class="for-sm-input-file" for="file"><i class="fas fa-upload"></i> Update
                                        Profile
                                        Picture</label>
                                    <span class="span-text" id="file-name"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-12">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="{{ $userdata->first_name }}">
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6 col-12">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="{{ $userdata->last_name }}">
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6 col-12">
                                <label for="email">Your E-Mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $userdata->email }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6 col-12">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="{{ $userdata->phone }}">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6 col-12">
                                <div class="gender-area">
                                    <h6>Your Gender</h6>
                                    <div class="d-flex">
                                        <label><input type="radio" class="input-radio" name="gender" value="1"
                                                {{ $userdata->gender == 1 ? 'checked' : '' }}> Male</label>
                                        <label><input type="radio" class="input-radio" name="gender" value="2"
                                                {{ $userdata->gender == 2 ? 'checked' : '' }}> Female</label>
                                    </div>
                                </div>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="inline-edit-btn main-btn">
                            <button type="submit" class="as_btn">Save Changes</button>
                            <button type="reset" class="as_btn cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Update Profile
            $('#updateProfileForm').on('submit', function(e) {
                e.preventDefault(); // Prevent form submission
                let formData = new FormData(this);
                let form = $(this);

                // Clear previous error messages
                form.find('.text-danger').remove();

                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('euser.updateProfile') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Add CSRF token to the request header
                    },
                    success: function(response) {
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                let input = form.find(`[name="${key}"]`);
                                input.after(
                                    `<div class="text-danger">${value[0]}</div>`);
                            });
                        } else {
                            toastr.error(xhr.responseJSON.error || 'An error occurred.');
                        }
                    }
                });
            });

            // Change Password
            $('#changePasswordForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                let formData = new FormData(this); // Get form data, including file input
                let form = $(this);

                // Clear previous error messages
                form.find('.text-danger').text('');

                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('euser.changePassword') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Add CSRF token to the request header
                    },
                    success: function(response) {
                        toastr.success(response.success); // Show success message
                        form.trigger("reset"); // Reset the form
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Validation error
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                form.find(`#${key}_error`).text(value[
                                    0]); // Show error under the field
                            });
                        } else if (xhr.status ===
                            400) { // Custom error (e.g., incorrect old password)
                            toastr.error(xhr.responseJSON.error);
                        }
                    }
                });
            });
        });
    </script>
@endsection
