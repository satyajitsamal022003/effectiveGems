<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            margin: 20px 0;
            color: #666;
        }
        .warning {
            color: #e74c3c;
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Processing Your Payment</h2>
        <div class="spinner"></div>
        <div class="message">Please wait while we confirm your payment...</div>
        <div class="warning">
            Don't close this window. If you've completed the payment, we're waiting for confirmation.
        </div>
    </div>

    <script>
        let attempts = 0;
        const maxAttempts = 30; // 30 attempts * 2 seconds = 60 seconds maximum wait
        
        function checkPaymentStatus() {
            if (attempts >= maxAttempts) {
                window.location.href = '{{ route("payment.failure") }}';
                return;
            }

            $.ajax({
                url: '/check-payment-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orderId: '{{ $orderId }}',
                    razorpayOrderId: '{{ $razorpayOrderId }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = '{{ route("payment.success") }}';
                    } else if (response.status === 'pending') {
                        attempts++;
                        setTimeout(checkPaymentStatus, 2000); // Check every 2 seconds
                        
                        // Show warning after 10 seconds
                        if (attempts === 5) {
                            $('.warning').fadeIn();
                        }
                    } else {
                        window.location.href = '{{ route("payment.failure") }}';
                    }
                },
                error: function() {
                    attempts++;
                    setTimeout(checkPaymentStatus, 2000);
                }
            });
        }

        // Start checking payment status
        $(document).ready(function() {
            checkPaymentStatus();
        });
    </script>
</body>
</html>
