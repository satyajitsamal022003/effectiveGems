<!DOCTYPE html>
<html>
<head>
    <title>Order Request</title>
</head>
<body>
    <h1>Request for Your Order #{{ $order->id }}</h1>
    <p>{{ $content }}</p> <!-- Ensure this is a string -->

    <p>
        <a href="{{ route('order.accept', ['id' => $order->id]) }}">Accept</a> |
        <a href="{{ route('order.cancel', ['id' => $order->id]) }}">Cancel</a>
    </p>

    <p>Thank you!</p>
</body>
</html>