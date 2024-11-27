@extends('user.layout')
@section('content')
@include('eusers.partials.header')
<section class="container mb-5">
        <div class="account-body">
            <div class="cart-page">
                <table class="rwd-table">
                        <tbody>
                        <tr>
                            <th>DELETE</th>
                            <th>PRODUCT IMAGE</th>
                            <th>PRODUCT NAME</th>
                            <th>STOCK STATUS</th>
                            <th>PRICE</th>
                            <th>ACTION</th>
                        </tr>
                        <tr>
                            <td data-th="DELETE">
                                <i class="fa-light fa-trash-can" style="color: red;cursor: pointer;padding-left: 15px;" title="Delete"></i>
                            </td>
                            <td data-th="PRODUCT IMAGE">
                                <img src="../assets/images/product-img-2.jpg" alt="image" class="pro-img">
                            </td>
                            <td data-th="PRODUCT NAME">
                                <a href="../product-details.html">Rudraksha Citrine Bracelet</a>
                            </td>
                            <td data-th="Stock Check">
                                In Stock
                            </td>
                            <td data-th="PRODUCT PRICE">
                                ₹2500
                            </td>
                            <td data-th="Action" class="main-btn">
                                <a href="#" CLASS="as_btn">Add to Cart</a>
                            </td>
                        </tr>
                        <!--end item-->
                        <tr>
                            <td data-th="DELETE">
                                <i class="fa-light fa-trash-can" style="color: red;cursor: pointer;padding-left: 15px;" title="Delete"></i>
                            </td>
                            <td data-th="PRODUCT IMAGE">
                                <img src="../assets/images/product-img-2.jpg" alt="image" class="pro-img">
                            </td>
                            <td data-th="PRODUCT NAME">
                                <a href="../product-details.html">Rudraksha Citrine Bracelet</a>
                            </td>
                            <td data-th="Stock Check">
                                In Stock
                            </td>
                            <td data-th="PRODUCT PRICE">
                                ₹2500
                            </td>
                            <td data-th="Action" class="main-btn">
                                <a href="#" CLASS="as_btn">Add to Cart</a>
                            </td>
                        </tr>
                        <!--end item-->
                        <tr>
                            <td data-th="DELETE">
                                <i class="fa-light fa-trash-can" style="color: red;cursor: pointer;padding-left: 15px;" title="Delete"></i>
                            </td>
                            <td data-th="PRODUCT IMAGE">
                                <img src="../assets/images/product-img-2.jpg" alt="image" class="pro-img">
                            </td>
                            <td data-th="PRODUCT NAME">
                                <a href="../product-details.html">Rudraksha Citrine Bracelet</a>
                            </td>
                            <td data-th="Stock Check">
                                In Stock
                            </td>
                            <td data-th="PRODUCT PRICE">
                                ₹2500
                            </td>
                            <td data-th="Action" class="main-btn">
                                <a href="#" CLASS="as_btn">Add to Cart</a>
                            </td>
                        </tr>
                        <!--end item-->

                        </tbody>
                    </table>
            </div>
        </div>
    </section>
    @endsection