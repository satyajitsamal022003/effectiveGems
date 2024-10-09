<!DOCTYPE html>
<html>
<head>
    <title>Order Approved</title>
</head>
<body>
    <h1>Congratulations!</h1>
    <p>Your order #{{ $order->id }} has been approved and is ready to dispatch.Here are your Order details</p>
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
</body>
</html>
