<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <style>
        .loading {
            cursor: wait !important;
        }
    </style>
</head>

<body>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function submitForm(response) {
            document.querySelector('#razorpay-payment-id').value = response.razorpay_payment_id;
            document.querySelector('#razorpay-order-id').value = response.razorpay_order_id;
            document.querySelector('#razorpay-signature').value = response.razorpay_signature;
            document.querySelector('#razorpay-form').submit();
        }

        var options = {
            key: "{{ env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T') }}",
            amount: "{{ isset($razorpayOrderId) ? $subtotal : 0 }}",
            currency: "INR",
            name: "Your Company Name",
            description: "Payment for your order",
            order_id: "{{ isset($razorpayOrderId) ? $razorpayOrderId : '' }}",
            notes: {
                order_id: "{{ $orderId }}"
            },
            retry: {
                enabled: true,
                max_count: 3
            },
            handler: function(response) {
                if (response.razorpay_payment_id) {
                    document.body.classList.add('loading');
                    setTimeout(function() {
                        submitForm(response);
                    }, 2000);
                }
            },
            modal: {
                confirm_close: true,
                escape: false,
                handleback: true,
                backdropclose: false,
                ondismiss: function() {
                    window.location.href = '{{ route("payment.failed") }}';
                }
            },
            prefill: {
                method: "upi"
            },
            config: {
                display: {
                    blocks: {
                        banks: {
                            name: "Pay using UPI",
                            instruments: [
                                {
                                    method: "upi"
                                }
                            ]
                        }
                    },
                    sequence: ["block.banks"],
                    preferences: {
                        show_default_blocks: false
                    }
                }
            }
        };

        var razorpay = new Razorpay(options);

        razorpay.on("payment.failed", function() {
            window.location.href = '{{ route("payment.failed") }}';
        });

        razorpay.on("payment.error", function() {
            window.location.href = '{{ route("payment.failed") }}';
        });

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
