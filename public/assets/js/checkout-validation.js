$(document).ready(function () {
    // Form validation function
    function validateForm() {
        let isValid = true;
        let errorMessages = [];

        const isUserLoggedIn = $('meta[name="auth-check"]').length > 0;

        // Validate shipping form
        const shippingFields = {
            'firstName': 'First Name',
            'lastName': 'Last Name',
            'address': 'Address',
            'landmark': 'Landmark',
            'city': 'City',
            'state': 'State',
            'zipcode': 'Zip Code'
        };

        // Validate shipping fields
        Object.keys(shippingFields).forEach(field => {
            const $field = $(`input[name="${field}"], select[name="${field}"]`);
            if ($field.length && $field.prop('required') && !$field.val()) {
                isValid = false;
                errorMessages.push(`${shippingFields[field]} is required`);
                $field.addClass('is-invalid');
            } else {
                $field.removeClass('is-invalid');
            }
        });

        // Email validation for non-authenticated users
        if (!$('meta[name="auth-check"]').length) {
            const email = $('input[name="email"]').val();
            if (!email) {
                isValid = false;
                errorMessages.push('Email Address is required');
                $('input[name="email"]').addClass('is-invalid');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                isValid = false;
                errorMessages.push('Please enter a valid email address');
                $('input[name="email"]').addClass('is-invalid');
            } else {
                $('input[name="email"]').removeClass('is-invalid');
            }
        }

        // Phone number validation
        const phone = $('input[name="phoneNumber"]').val();
        if (!isUserLoggedIn && phone && !/^\d{10}$/.test(phone)) {
            isValid = false;
            errorMessages.push('Please enter a valid 10-digit phone number');
            $('input[name="phoneNumber"]').addClass('is-invalid');
        } else {
            $('input[name="phoneNumber"]').removeClass('is-invalid');
        }

        // Validate billing form if different billing address is selected
        if ($('input[name="sameAddress"]:checked').val() === '0') {
            const billingFields = {
                'billingfirstName': 'Billing First Name',
                'billinglastName': 'Billing Last Name',
                'billingaddress': 'Billing Address',
                'billinglandmark': 'Billing Landmark',
                'billingcity': 'Billing City',
                'billingstate': 'Billing State',
                'billingzipcode': 'Billing Zip Code',
                'billingphoneNumber': 'Billing Phone Number',
                'billingcountry': 'Billing Country'
            };

            // Validate billing fields
            Object.keys(billingFields).forEach(field => {
                const $field = $(`input[name="${field}"], select[name="${field}"]`);
                if ($field.length && !$field.val()) {
                    isValid = false;
                    errorMessages.push(`${billingFields[field]} is required`);
                    $field.addClass('is-invalid');
                } else {
                    $field.removeClass('is-invalid');
                }
            });

            // Billing email validation
            const billingEmail = $('input[name="billingamount"]').val();
            if (!billingEmail) {
                isValid = false;
                errorMessages.push('Billing Email Address is required');
                $('input[name="billingamount"]').addClass('is-invalid');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(billingEmail)) {
                isValid = false;
                errorMessages.push('Please enter a valid billing email address');
                $('input[name="billingamount"]').addClass('is-invalid');
            } else {
                $('input[name="billingamount"]').removeClass('is-invalid');
            }

            // Billing phone validation
            const billingPhone = $('input[name="billingphoneNumber"]').val();
            if (billingPhone && !/^\d{10}$/.test(billingPhone)) {
                isValid = false;
                errorMessages.push('Please enter a valid 10-digit billing phone number');
                $('input[name="billingphoneNumber"]').addClass('is-invalid');
            }
        }

        // Terms and conditions validation
        if (!$('#term_condition').is(':checked')) {
            isValid = false;
            errorMessages.push('Please accept the Terms and Conditions');
            $('#term_condition').addClass('is-invalid');
        } else {
            $('#term_condition').removeClass('is-invalid');
        }

        // Display error messages if any
        if (!isValid) {
            alert(errorMessages.join('\n'));
        }

        return isValid;
    }

    // Override the existing AJAX form submission
    $('#orderForm').off('submit').on('submit', function (e) {
        e.preventDefault(); // Always prevent default submission first

        if (!validateForm()) {
            return false; // Stop here if validation fails
        }

        // If validation passes, proceed with AJAX submission
        $.ajax({
            url: "/checkout",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function () {
                $('#submitOrder').prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                if (response.otp_required) {
                    $("#otpModal").modal("show");
                } else if (response.success && response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else {
                    alert("Something went wrong. Please try again.");
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let errorMessage = "An error occurred.";
                if (errors) {
                    errorMessage = Object.values(errors).join("\n");
                }
                alert(errorMessage);
                $('#submitOrder').prop('disabled', false).text('Place Order');
            }
        });

        return false; // Prevent form submission
    });

    // Real-time validation on input change
    $('input, select').on('change', function () {
        if ($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid');
        }
    });

    // Toggle billing address form visibility
    $('input[name="sameAddress"]').on('change', function () {
        if ($(this).val() === '0') {
            $('.billingAddress').show();
            // Add required attribute to billing fields
            $('.billingAddress input, .billingAddress select').prop('required', true);
        } else {
            $('.billingAddress').hide();
            // Remove required attribute from billing fields
            $('.billingAddress input, .billingAddress select').prop('required', false);
        }
    });

    // Phone number input validation
    $('input[type="tel"]').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
    });

    // Initialize form state
    if ($('input[name="sameAddress"]:checked').val() === '0') {
        $('.billingAddress').show();
        $('.billingAddress input, .billingAddress select').prop('required', true);
    }
});
