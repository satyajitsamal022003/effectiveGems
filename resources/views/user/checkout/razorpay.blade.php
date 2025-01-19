<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let orderId = "{{ isset($razorpayOrderId) ? $razorpayOrderId : '' }}";
        let isPolling = false;
        let pollInterval = null;

        // Function to check payment status
        function checkPaymentStatus() {
            if (!orderId) return;

            $.get(`/check-payment-status/${orderId}`)
                .done(function(response) {
                    if (response.status === 'paid') {
                        stopPolling();
                        // Submit form with payment details
                        document.querySelector('#razorpay-payment-id').value = response.payment_id;
                        document.querySelector('#razorpay-order-id').value = response.order_id;
                        document.querySelector('#razorpay-signature').value = response.signature;
                        document.querySelector('#razorpay-form').submit();
                    } else if (response.payment_status === 'failed') {
                        stopPolling();
                        window.location.href = "{{ route('payment.failed') }}";
                    }
                })
                .fail(function() {
                    stopPolling();
                    window.location.href = "{{ route('payment.failed') }}";
                });
        }

        // Start polling
        function startPolling() {
            if (!isPolling && orderId) {
                isPolling = true;
                pollInterval = setInterval(checkPaymentStatus, 3000); // Check every 3 seconds
                // Stop polling after 5 minutes (300000 ms) if payment not completed
                setTimeout(stopPolling, 300000);
            }
        }

        // Stop polling
        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                isPolling = false;
            }
        }

        const options = {
            "key": "{{ env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T') }}", // Razorpay Key ID
            "amount": "{{ isset($razorpayOrderId) ? $subtotal : 0 }}", // Amount in paisa
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Payment for your order",
            "order_id": "{{ isset($razorpayOrderId) ? $razorpayOrderId : '' }}", // Razorpay order ID
            "handler": function(response) {
                // For redirect-based payments (cards, UPI collect, etc.)
                document.querySelector('#razorpay-payment-id').value = response.razorpay_payment_id;
                document.querySelector('#razorpay-order-id').value = response.razorpay_order_id;
                document.querySelector('#razorpay-signature').value = response.razorpay_signature;
                document.querySelector('#razorpay-form').submit();
            },
            "modal": {
                "ondismiss": function() {
                    stopPolling();
                    // Only redirect if we're not in the middle of processing a payment
                    if (!isPolling) {
                        window.location.href = "{{ route('payment.failed') }}";
                    }
                }
            },
            "callback_url": "{{ route('razorpay.callback') }}",
            "prefill": {
                "name": "{{ $name ?? '' }}",
                "email": "{{ $email ?? '' }}",
                "contact": "{{ $phone ?? '' }}"
            }
        };

        const razorpay = new Razorpay(options);
        
        // Listen for payment events
        razorpay.on('payment.failed', function(response) {
            stopPolling();
            window.location.href = "{{ route('payment.failed') }}";
        });

        // Start polling when payment modal opens
        startPolling();

        razorpay.open();
    </script>

    <form id="razorpay-form" action="{{ route('razorpay.callback') }}" method="POST">
        @csrf
        <input type="hidden" name="orderId" value="{{ $orderId }}">
        <input type="hidden" name="razorpay_payment_id" id="razorpay-payment-id">
        <input type="hidden" name="razorpay_order_id" id="razorpay-order-id">
        <input type="hidden" name="razorpay_signature" id="razorpay-signature">
    </form>
</body>

</html>
