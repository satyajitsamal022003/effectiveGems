<!DOCTYPE html>
<html>
<head>
    <title>Order Request</title>
</head>
<body>
    <h1>Request for Your Order #{{ $order->id }}</h1>

    <p>{{ $content }}</p> 

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
    
    <p>
        <a href="{{ route('order.accept', ['id' => $order->id]) }}">Accept</a> |
        <a href="{{ route('order.cancel', ['id' => $order->id]) }}">Cancel</a>
    </p>
    
    <p>Thank you!</p>
</body>
</html>
