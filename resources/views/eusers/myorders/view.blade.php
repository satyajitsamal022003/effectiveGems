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
                                <span>Name: Ram Shankar Dash</span>
                                <span>Phone Number: +91 1234567890</span>
                                <span>Delivered: 16-Feb-2024</span>
                                <span>Shipping Address:
                                    Near Binapani Coaching Centre Saheed Nagar Khordha - 750017, Bhubaneswar, Odisha, India </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="details_text">
                            <div class="pro-text">
                                <h3>Order Summary</h3>
                                <span>Payment Methods: Online</span>
                                <span>Subtotal: ₹2500.00</span>
                                <span>Shipping Charges: ₹40.00</span>
                                <h6 class="mt-1">Grand Total:  ₹2500.00</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="details_text">
                            <div class="pro-text">
                                <span>Order Id: #171-8009191-8965143</span>
                                <span>Ordered: 12-Feb-2024</span>
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
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="details_text">
                            <img src="../assets/images/product-img-2.jpg" alt="image" class="details-pro-img">
                            <div class="pro-text">
                                <h3>Rudraksha Citrine Bracelet</h3>
                                <span>₹2500.00</span>
                                <span>Activation Charge : ₹250.00</span>
                                <span>Certificate Charge : ₹1500.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="details_text">
                            <img src="../assets/images/product-img-2.jpg" alt="image" class="details-pro-img">
                            <div class="pro-text">
                                <h3>Rudraksha Citrine Bracelet</h3>
                                <span>₹2500.00</span>
                                <span>Activation Charge : ₹250.00</span>
                                <span>Certificate Charge : ₹1500.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="details_text">
                            <img src="../assets/images/product-img-2.jpg" alt="image" class="details-pro-img">
                            <div class="pro-text">
                                <h3>Rudraksha Citrine Bracelet</h3>
                                <span>₹2500.00</span>
                                <span>Activation Charge : ₹250.00</span>
                                <span>Certificate Charge : ₹1500.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection