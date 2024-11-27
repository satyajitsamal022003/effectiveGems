@extends('user.layout')
@section('content')
@include('eusers.partials.header')
<section class="container mb-5">
        <div class="account-body">
            <div  class="order-filter mb-3">
                <label>Filter Order Status</label>
                <select class="form-select" aria-label="Default select example">
                    <option selected disabled>Order Status</option>
                    <option value="1">Processing</option>
                    <option value="2">Deliver</option>
                    <option value="3">Cancel</option>
                </select>
            </div>
            <div class="cart-page">
                <table class="rwd-table">
                        <tbody>
                        <tr>
                            <th>ORDER ID</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                            <th>PRICE</th>
                            <th>ACTION</th>
                        </tr>
                        <tr>
                            <td data-th="ORDER ID">
                                #01112
                            </td>
                            <td data-th="DATE">
                                Feb 15, 2024
                            </td>
                            <td data-th="STATUS">
                                <!--use status class name cancel, success, process-->
                                <span class="process">Processing</span>
                            </td>
                            <td data-th="PRODUCT PRICE">
                                ₹2500
                            </td>
                            <td data-th="ACTION" class="main-btn">
                                <a href="my-order-details.html" class="as_btn">View Details</a>
                            </td>
                        </tr>
                        <!--end item-->
                        <tr>
                            <td data-th="ORDER ID">
                                #01112
                            </td>
                            <td data-th="DATE">
                                Feb 15, 2024
                            </td>
                            <td data-th="STATUS">
                                <!--use status class name cancel, success, process-->
                                <span class="success">Deliver</span>
                            </td>
                            <td data-th="PRODUCT PRICE">
                                ₹2500
                            </td>
                            <td data-th="ACTION" class="main-btn">
                                <a href="my-order-details.html" class="as_btn">View Details</a>
                            </td>
                        </tr>
                        <!--end item-->
                        <tr>
                            <td data-th="ORDER ID">
                                #01112
                            </td>
                            <td data-th="DATE">
                                Feb 15, 2024
                            </td>
                            <td data-th="STATUS">
                                <!--use status class name cancel, success, process-->
                                <span class="cancel">Cancel</span>
                            </td>
                            <td data-th="PRODUCT PRICE">
                                ₹2500
                            </td>
                            <td data-th="ACTION" class="main-btn">
                                <a href="my-order-details.html" class="as_btn">View Details</a>
                            </td>
                        </tr>
                        <!--end item-->

                        </tbody>
                    </table>
            </div>
        </div>
    </section>
@endsection