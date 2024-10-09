<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Congratulations!</h1>
        <p>Your order has been placed successfully.</p>
        <p>Order ID: {{ $order->id }}</p>
        <ul>
        @php
            $orderItemsWithProducts = DB::table('order_items')
                ->join('product', 'order_items.proId', '=', 'product.id')
                ->where('order_items.orderId', $order->id)
                ->select('order_items.*', 'product.productName')
                ->get();
        @endphp

        @foreach ($orderItemsWithProducts as $item)
            <li>Product: {{ $item->productName }}</li>
        @endforeach
    </ul>
        <p>Thank you for your purchase!</p>
    </div>
</body>
</html>
