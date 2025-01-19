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
        let pollCount = 0;
        const MAX_POLLS = 240; // 2 minutes (with 500ms interval)
        let pollInterval;

        // Function to check order status using Razorpay's QR status API
        function checkOrderStatus(paymentId) {
            var checkUrl = 'https://api.razorpay.com/v1/payments/' + paymentId;
            
            fetch(checkUrl, {
                headers: {
                    'Authorization': 'Basic ' + btoa('{{ env('RAZORPAY_KEY') }}:{{ env('RAZORPAY_SECRET') }}')
                }
            })
            .then(function(response) { 
                return response.json(); 
            })
            .then(function(data) {
                if (data.status === 'captured' || data.status === 'authorized') {
                    clearInterval(pollInterval);
                    // Submit the form with payment details
                    document.querySelector('#razorpay-payment-id').value = paymentId;
                    document.querySelector('#razorpay-order-id').value = data.order_id;
                    document.querySelector('#razorpay-form').submit();
                }
                
                pollCount++;
                if (pollCount >= MAX_POLLS) {
                    clearInterval(pollInterval);
                    window.location.href = '{{ route('payment.failure') }}';
                }
            })
            .catch(function(error) {
                console.error('Error checking payment status:', error);
            });
        }

        const options = {
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
                    clearInterval(pollInterval);
                    window.location.href = '{{ route('payment.failure') }}';
                }
            },
            "callback_url": "{{ route('razorpay.callback') }}",
        };

        const razorpay = new Razorpay(options);
        razorpay.open();

        // Handle QR code payments
        razorpay.on('payment.failed', function(response) {
            clearInterval(pollInterval);
            window.location.href = '{{ route('payment.failure') }}';
        });

        razorpay.on('payment.error', function(response) {
            clearInterval(pollInterval);
            window.location.href = '{{ route('payment.failure') }}';
        });

        // Start polling when QR is scanned
        razorpay.on('qr.scanned', function(response) {
            if (response.payment_id) {
                pollInterval = setInterval(function() {
                    checkOrderStatus(response.payment_id);
                }, 2000); // Poll every 2 seconds instead of 500ms
            }
        });

        // Also check status when payment is processing
        razorpay.on('payment.processing', function(response) {
            if (response.payment_id) {
                pollInterval = setInterval(function() {
                    checkOrderStatus(response.payment_id);
                }, 2000);
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
