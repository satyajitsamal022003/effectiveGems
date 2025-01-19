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
        var POLL_INTERVAL = 2000; // 2 seconds
        var MAX_POLL_TIME = 300000; // 5 minutes
        var startTime = Date.now();
        var paymentInProgress = false;

        function submitPaymentForm(paymentId, orderId, signature) {
            // Prevent duplicate submissions
            if (document.querySelector('#razorpay-payment-id').value) {
                return;
            }

            // Clear polling
            if (pollTimer) {
                clearTimeout(pollTimer);
                pollTimer = null;
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
            if (!paymentInProgress) {
                return;
            }

            // Check timeout
            if (Date.now() - startTime >= MAX_POLL_TIME) {
                paymentInProgress = false;
                if (pollTimer) {
                    clearTimeout(pollTimer);
                }
                window.location.href = '{{ route('payment.failed') }}';
                return;
            }

            fetch('{{ route('razorpay.callback') }}?razorpay_payment_id=' + paymentId)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data) {
                    console.log('Payment status:', data);
                    
                    if (data.status === 'captured' || data.status === 'authorized' || 
                        (data.is_upi && data.status === 'processed')) {
                        paymentInProgress = false;
                        submitPaymentForm(paymentId, data.order_id);
                    } else if (paymentInProgress) {
                        pollTimer = setTimeout(function() {
                            checkPaymentStatus(paymentId);
                        }, POLL_INTERVAL);
                    }
                })
                .catch(function(error) {
                    console.error('Error checking payment status:', error);
                    if (paymentInProgress) {
                        pollTimer = setTimeout(function() {
                            checkPaymentStatus(paymentId);
                        }, POLL_INTERVAL);
                    }
                });
        }

        var options = {
            "key": "{{ env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T') }}",
            "amount": "{{ isset($razorpayOrderId) ? $subtotal : 0 }}",
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Payment for your order",
            "order_id": "{{ isset($razorpayOrderId) ? $razorpayOrderId : '' }}",
            "notes": {
                "order_id": "{{ $orderId }}"
            },
            "handler": function(response) {
                if (response.razorpay_payment_id) {
                    paymentInProgress = false;
                    submitPaymentForm(
                        response.razorpay_payment_id,
                        response.razorpay_order_id,
                        response.razorpay_signature
                    );
                }
            },
            "modal": {
                "ondismiss": function() {
                    paymentInProgress = false;
                    if (pollTimer) {
                        clearTimeout(pollTimer);
                    }
                    window.location.href = '{{ route('payment.failed') }}';
                }
            },
            "prefill": {
                "method": "upi"
            }
        };

        var razorpay = new Razorpay(options);
        razorpay.open();

        // Handle various payment events
        razorpay.on('payment.failed', function(response) {
            console.log('Payment failed:', response);
            paymentInProgress = false;
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
            window.location.href = '{{ route('payment.failed') }}';
        });

        razorpay.on('payment.error', function(response) {
            console.log('Payment error:', response);
            paymentInProgress = false;
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
            window.location.href = '{{ route('payment.failed') }}';
        });

        razorpay.on('payment.captured', function(response) {
            console.log('Payment captured:', response);
            if (response.razorpay_payment_id) {
                paymentInProgress = false;
                submitPaymentForm(
                    response.razorpay_payment_id,
                    response.razorpay_order_id,
                    response.razorpay_signature
                );
            }
        });

        // Start polling on these events
        razorpay.on('qr.scanned', function(response) {
            console.log('QR scanned:', response);
            if (response.payment_id) {
                paymentInProgress = true;
                checkPaymentStatus(response.payment_id);
            }
        });

        razorpay.on('payment.processing', function(response) {
            console.log('Payment processing:', response);
            if (response.payment_id) {
                paymentInProgress = true;
                checkPaymentStatus(response.payment_id);
            }
        });

        // Additional UPI-specific events
        razorpay.on('payment.submit', function(response) {
            console.log('Payment submit:', response);
        });

        razorpay.on('payment.app.open', function(response) {
            console.log('Payment app opened:', response);
        });

        razorpay.on('payment.app.closed', function(response) {
            console.log('Payment app closed:', response);
        });

        // Cleanup
        window.addEventListener('beforeunload', function() {
            paymentInProgress = false;
            if (pollTimer) {
                clearTimeout(pollTimer);
            }
        });

        razorpay.on('modal.closed', function() {
            console.log('Modal closed');
            paymentInProgress = false;
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
