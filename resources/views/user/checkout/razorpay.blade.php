<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
</head>

<body>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var pollTimer = null;
        var POLL_INTERVAL = 3000; // 3 seconds
        var MAX_POLL_TIME = 300000; // 5 minutes
        var startTime = Date.now();

        function submitPaymentForm(paymentId, orderId, signature) {
            // Clear any existing polling
            if (pollTimer) {
                clearTimeout(pollTimer);
                pollTimer = null;
            }

            // Prevent duplicate submissions
            if (document.querySelector('#razorpay-payment-id').value) {
                return;
            }

            // Submit the form
            document.querySelector('#razorpay-payment-id').value = paymentId;
            if (orderId) {
                document.querySelector('#razorpay-order-id').value = orderId;
            }
            if (signature) {
                document.querySelector('#razorpay-signature').value = signature;
            }
            document.querySelector('#razorpay-form').submit();
        }

        function checkPaymentStatus(paymentId) {
            // Check if we've exceeded the maximum polling time
            if (Date.now() - startTime >= MAX_POLL_TIME) {
                if (pollTimer) {
                    clearTimeout(pollTimer);
                }
                window.location.href = '{{ route('payment.failed') }}';
                return;
            }

            fetch('{{ route('razorpay.callback') }}?razorpay_payment_id=' + paymentId, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.status === 'captured' || data.status === 'authorized') {
                    submitPaymentForm(paymentId, data.order_id);
                } else {
                    // Continue polling
                    pollTimer = setTimeout(function() {
                        checkPaymentStatus(paymentId);
                    }, POLL_INTERVAL);
                }
            })
            .catch(function(error) {
                console.error('Error checking payment status:', error);
                // Continue polling on error
                pollTimer = setTimeout(function() {
                    checkPaymentStatus(paymentId);
                }, POLL_INTERVAL);
            });
        }

        var options = {
            "key": "{{ env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T') }}", // Razorpay Key ID
            "amount": "{{ isset($razorpayOrderId) ? $subtotal : 0 }}", // Amount in paisa
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Payment for your order",
            "order_id": "{{ isset($razorpayOrderId) ? $razorpayOrderId : '' }}", // Razorpay order ID
            "notes": {
                "order_id": "{{ $orderId }}"
            },
            "handler": function(response) {
                if (response.razorpay_payment_id) {
                    // For immediate payment methods (cards, netbanking, etc.)
                    document.querySelector('#razorpay-payment-id').value = response.razorpay_payment_id;
                    document.querySelector('#razorpay-order-id').value = response.razorpay_order_id;
                    document.querySelector('#razorpay-signature').value = response.razorpay_signature;
                    document.querySelector('#razorpay-form').submit();
                }
            },
            "modal": {
                "ondismiss": function() {
                    if (pollTimer) {
                        clearTimeout(pollTimer);
                    }
                    window.location.href = '{{ route('payment.failed') }}';
                }
            },
            "callback_url": "{{ route('razorpay.callback') }}"
        };

        var razorpay = new Razorpay(options);
        razorpay.open();

        // Handle payment failures
        razorpay.on('payment.failed', function(response) {
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
            window.location.href = '{{ route('payment.failed') }}';
        });

        razorpay.on('payment.error', function(response) {
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
            window.location.href = '{{ route('payment.failed') }}';
        });

        // Handle QR code and processing events
        razorpay.on('qr.scanned', function(response) {
            if (response.payment_id) {
                checkPaymentStatus(response.payment_id);
            }
        });

        razorpay.on('payment.processing', function(response) {
            if (response.payment_id) {
                checkPaymentStatus(response.payment_id);
            }
        });

        // Cleanup on page unload or modal close
        window.addEventListener('beforeunload', function() {
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
        });

        razorpay.on('modal.closed', function() {
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
        });
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
