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
        const options = {
            "key": "{{ env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T') }}", // Razorpay Key ID
            "amount": "{{ isset($razorpayOrderId) ? $subtotal : 0 }}", // Amount in paisa
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Payment for your order",
            "order_id": "{{ isset($razorpayOrderId) ? $razorpayOrderId : '' }}", // Razorpay order ID
            "handler": function(response) {
                // Submit the payment form
                document.querySelector('#razorpay-payment-id').value = response.razorpay_payment_id;
                document.querySelector('#razorpay-order-id').value = response.razorpay_order_id;
                document.querySelector('#razorpay-signature').value = response.razorpay_signature;
                document.querySelector('#razorpay-form').submit();
            },
        };

        const razorpay = new Razorpay(options);
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
