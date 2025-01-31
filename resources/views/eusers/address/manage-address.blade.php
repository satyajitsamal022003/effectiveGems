@extends('user.layout')
@section('title', 'Manage Address')
@section('content')
@include('eusers.partials.header')
<section class="container mb-5">
    <div class="account-body manage-address">
        <div class="profile-info">
            <div class="address-info">
                @if ($addressdata->isNotEmpty())
                @foreach ($addressdata as $addressdatas)
                <div class="profile-img">
                    <div class="text-3">
                        <span>{{ $addressdatas->address_type }}</span>
                        <h3>{{ $addressdatas->first_name }} {{ $addressdatas->middle_name }}
                            {{ $addressdatas->last_name }}
                        </h3>
                        <h6 class="d-block">{{ $addressdatas->address }} - {{ $addressdatas->zip_code }},
                            {{ $addressdatas->city_name }}, {{ $addressdatas->state->stateName }},
                            {{ $addressdatas->country_id == 1 ? 'India' : 'N/A' }}
                        </h6>
                    </div>
                    <!-- Edit Button -->
                    <a href="{{ route('euser.address.edit', $addressdatas->id) }}" class="edit-btn">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>

                    <!-- Delete Link -->
                    <a href="javascript:void(0);" class="delete-btn" data-id="{{ $addressdatas->id }}">
                        <i class="fa-regular fa-trash-alt"></i> Delete
                    </a>

                </div>
                @endforeach
                @else
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="fa-regular fa-map"></i>
                        <h5 class="mt-3">No Addresses Found</h5>
                        <p class="text-muted">You have not added any addresses yet.</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="profile-edit-section">
                @if(isset($address))
                <h4 class="">Edit ADDRESS</h4>
                @else
                <h4 class="">ADD A NEW ADDRESS</h4>
                @endif
                <form id="addAddressForm"
                    action="{{ isset($address) ? route('euser.address.update', $address->id) : route('euser.address.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($address))
                    @method('PUT') <!-- This will tell Laravel it's an update request -->
                    @endif
                    @csrf
                    <div class="row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="f-name">First Name:</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                        value="{{ old('first_name', isset($address) ? $address->first_name : '') }}" />
                                    @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="l-name">Middle Name:</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control"
                                        value="{{ old('middle_name', isset($address) ? $address->middle_name : '') }}" />
                                    @error('middle_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="l-name">Last Name:</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                        value="{{ old('last_name', isset($address) ? $address->last_name : '') }}" />
                                    @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="p-no">Phone Number:</label>
                                    <input type="tel" maxlength="10" name="phone" id="p-no"
                                        class="form-control"
                                        value="{{ old('phone', isset($address) ? $address->phone : '') }}" />
                                    @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <!-- Country/Region -->
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="country-id">Country/Region:</label>
                                    <select class="form-select" id="country-id" name="country_id"
                                        aria-label="Default select example">
                                        <option selected disabled>Choose Country</option>
                                        <option value="1"
                                            {{ old('country_id', $address->country_id ?? '') == 1 ? 'selected' : '' }}>
                                            India</option>
                                    </select>
                                    @error('country_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- State -->
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="state-id">State:</label>
                                    <select class="form-select" id="state-id" name="state_id"
                                        aria-label="Default select example">
                                        <option selected disabled>Choose State</option>
                                        @foreach ($state as $statedata)
                                        <option value="{{ $statedata->id }}"
                                            {{ old('state_id', $address->state_id ?? '') == $statedata->id ? 'selected' : '' }}>
                                            {{ $statedata->stateName }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('state_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="city-name">City:</label>
                                    <input type="text" name="city_name" id="city-name" class="form-control"
                                        value="{{ old('city_name', isset($address) ? $address->city_name : '') }}" />
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="postal-code">Zip / Postal Code:</label>
                                    <input type="text" name="zip_code" id="postal-code" class="form-control"
                                        value="{{ old('zip_code', isset($address) ? $address->zip_code : '') }}" />
                                    @error('city_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="postal-code">Landmark:</label>
                                    <input type="text" name="landmark" id="postal-code" class="form-control"
                                        value="{{ old('landmark', isset($address) ? $address->landmark : '') }}" />
                                    @error('landmark')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <div class="gender-area">
                                        <h6>Address Type:</h6>
                                        <div class="d-flex">
                                            <label>
                                                <input type="radio" class="input-radio" name="address_type" value="1"
                                                    {{ old('address_type', $address->address_type ?? '') == 'Home' ? 'checked' : '' }}>
                                                Home
                                            </label>
                                            <label>
                                                <input type="radio" class="input-radio" name="address_type" value="2"
                                                    {{ old('address_type', $address->address_type ?? '') == 'Work' ? 'checked' : '' }}>
                                                Work
                                            </label>
                                        </div>
                                        @error('address_type')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-12">
                                    <label for="address_2">Apartment/Suite/Building (Optional):</label>
                                    <input type="text" name="apartment" id="address_2" class="form-control"
                                        value="{{ old('apartment', isset($address) ? $address->apartment : '') }}">
                                    @error('apartment')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-12">
                                    <label for="address_1">Address:</label>
                                    <textarea type="text" name="address" id="address_1" class="form-control" value="">{{ old('address', isset($address) ? $address->address : '') }}</textarea>
                                    @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="inline-edit-btn main-btn">
                        <button type="submit"
                            class="as_btn">{{ isset($address) ? 'Update Address' : 'Save Address' }}</button>
                        <button type="reset" class="as_btn cancel-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="{{ url('/') }}/user/assets/js/jquery.js"></script>
<script>
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true, // Optional: adds a close button
            "progressBar": true, // Optional: adds a progress bar
            "timeOut": 1000, // Toastr will display for 1 second before disappearing
            "extendedTimeOut": 1000, // Time for Toastr to remain open after mouse hover (optional)
            "hideDuration": 1000, // Duration of the Toastr hide animation
            "showDuration": 1000 // Duration of the Toastr show animation
        };
        $('#addAddressForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let formData = new FormData(this);
            form.find('.text-danger').remove();
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    toastr.success(response.success);
                    setTimeout(function() {
                        window.location.href =
                            "{{ route('euser.manageaddress') }}"; // Redirect after 1 second
                    }, 1000);
                    form.trigger("reset");
                    $('.form-control').removeClass('is-invalid');
                    $('.text-danger').text('');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            let field = $(`[name="${key}"]`);
                            field.addClass('is-invalid');
                            field.siblings('.text-danger').text(value[0]);
                        });
                    } else {
                        toastr.error("An unexpected error occurred.");
                    }
                }
            });
        });
        $('.delete-btn').on('click', function() {
            const addressId = $(this).data('id');
            const url = "{{ route('euser.address.destroy', ':id') }}".replace(':id', addressId);

            if (confirm('Are you sure you want to delete this address?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('euser.manageaddress') }}"; // Redirect after 1 second
                            }, 1000);
                            // Optionally, remove the deleted address from the DOM
                            $(`.delete-btn[data-id="${addressId}"]`).closest('.profile-img')
                                .remove();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'An error occurred.');
                    }
                });
            }
        });
    });
    // document.addEventListener('DOMContentLoaded', function() {
    //     const deleteForms = document.querySelectorAll('.delete-form');

    //     deleteForms.forEach(form => {
    //         form.addEventListener('submit', function(e) {
    //             e.preventDefault();
    //             if (confirm('Are you sure you want to delete this address?')) {
    //                 form.submit();
    //             }
    //         });
    //     });
    // });
</script>
@endsection