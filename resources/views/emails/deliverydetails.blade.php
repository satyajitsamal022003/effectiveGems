<!DOCTYPE html>
<html>

<head>
    <title>Order Information</title>
</head>

<body>
    <h1>Dear customer,</h1>
    <p>We are pleased to inform you that your order is ready to deliver. Here are the details:</p>
    <ul>
        @php
            // Decode the courier details from JSON
            $deliverydetails = json_decode($order->deliverydetails, true);
        @endphp

        @php
            $orderItemsWithProducts = DB::table('order_items')
                ->join('product', 'order_items.proId', '=', 'product.id')
                ->where('order_items.orderId', $order->id)
                ->select('order_items.*', 'product.productName')
                ->get();
        @endphp

        @foreach ($orderItemsWithProducts as $item)
            <li><strong>Product: {{ $item->productName }}</strong></li>
        @endforeach

        <li><strong>Delivery Date:</strong> {{ $deliverydetails['deliverydate'] ?? 'N/A' }}</li>
        <li><strong>Receivers Name:</strong> {{ $deliverydetails['receivedby'] ?? 'N/A' }}</li>

        <p>Thank you for your patience. If you have any further questions, feel free to contact us.</p>

        
    </ul>
    <p>Thank you for your purchase!</p>
</body>

</html>
