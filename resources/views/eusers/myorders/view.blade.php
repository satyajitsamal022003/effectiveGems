@extends('user.layout')
@section('content')
    @include('eusers.partials.header')
    <section class="container mb-5">
        <div class="account-body">

            <div class="order-details-item mb-3">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="details_text">
                            <div class="pro-text">
                                <h3>Shipping Info</h3>
                                <span>Name: {{ $order->firstName . ' ' . $order->lastName }}</span>
                                <span>Phone Number:{{ $order->phoneNumber }}</span>
                                <span>Delivered: 16-Feb-2024</span>
                                <span>Shipping Address:
                                    {{ $order->address }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="details_text">
                            <div class="pro-text">
                                <h3>Order Summary</h3>
                                <span>Payment Methods: {{ $order->paymentMode ?? 'N/A' }}</span>
                                <span>Subtotal: ₹{{ $order->amount - $order->shippingAmount }}</span>
                                <span>Shipping Charges: {{ $order->shippingAmount ?? 'N/A' }}</span>
                                <h6 class="mt-1">Grand Total: ₹{{ $order->amount }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="details_text">
                            <div class="pro-text">
                                <span>Order Id: #{{ $order->id }}</span>
                                <span>Ordered: {{ $order->created_at->format('M d, Y, h:i A') }}</span>
                            </div>
                        </div>
                        <div class="details_text">
                            <div class="pro-text action-area">
                                <span><a href="../assets/images/invoice.pdf" target="_blank">
                                        <img src="../assets/images/downloadInvoice.png" alt="image">Invoice</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <div class="row">
                    @foreach ($order->items as $item)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="details_text">
                                <img src="../assets/images/product-img-2.jpg" alt="image" class="details-pro-img">
                                <div class="pro-text">
                                    <h3>{{ $item->productDetails->productName }}</h3>
                                    <span>Quantity :{{ $item->quantity }}</span>
                                    <span>₹{{ $item->productDetails->priceB2C * $item->quantity + $item->activation + $item->certificate }}</span>
                                    <span>Activation Charge : ₹{{ $item->activation }}</span>
                                    <span>Certificate Charge : ₹{{ $item->certificate }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </section>
@endsection
