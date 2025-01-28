@extends('user.layout')
@section('content')
    @include('eusers.partials.header')
    <section class="container mb-5">
        <div class="account-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12">
                    <a href="{{route('euser.myorderlist')}}" class="card_box">
                        <img src="assets/images/my-orders.png" alt="image">
                        <h4>My Orders</h4>
                        <span>{{ $orderCount }}</span>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <a href="{{route('euser.wishlist')}}" class="card_box">
                        <img src="assets/images/wishlist.png" alt="image">
                        <h4>My Wishlist</h4>
                        <span>05</span>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <a href="{{route('euser.myProfile')}}" class="card_box">
                        <img src="assets/images/profile-img.jpg" alt="image">
                        <h4>{{ $user->first_name ." ". $user->last_name }}</h4>
                        <span>View Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
