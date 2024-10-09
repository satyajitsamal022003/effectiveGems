<!DOCTYPE html>
<html>
<head>
    <title>Order Canceled</title>
</head>
<body>
<h1>Your Order #{{ $order->id }} has been Canceled</h1>
    @if($cancellationReason)
        <p>Reason for cancellation: {{ $cancellationReason }}</p>
    @endif
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
    <p>Thank you!</p>
</body>
</html>
